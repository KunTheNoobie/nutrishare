<?php

namespace App\Repositories;

use App\Models\Donation;
use Illuminate\Support\Facades\DB;

/**
 * SECURITY (Module 1): SQL Injection Prevention
 *
 * OWASP Reference: A03 Injection
 *
 * This repository demonstrates BOTH Eloquent parameterized queries AND
 * explicit PDO parameter binding for custom queries. All user inputs
 * are bound as parameters, never concatenated into SQL strings.
 */
class DonationRepository
{
    /**
     * Find active donations using Eloquent's parameterized queries.
     * Eloquent automatically uses prepared statements with bound parameters.
     */
    public function findActiveDonations(array $filters = [])
    {
        $query = Donation::with(['donor', 'foodItems'])
            ->where('status', 'available') // Parameterized by Eloquent
            ->where('expiry_date', '>', now());

        if (!empty($filters['search'])) {
            // SECURE: Eloquent automatically binds this parameter
            $query->where('title', 'LIKE', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['min_quantity'])) {
            // SECURE: Parameterized query via Eloquent
            $query->where('quantity', '>=', $filters['min_quantity']);
        }

        return $query->orderBy('created_at', 'desc')->paginate(15);
    }

    /**
     * SECURITY DEMONSTRATION: Explicit PDO parameter binding.
     *
     * This method uses raw SQL with explicit PDO parameter binding to demonstrate
     * that even when writing custom queries outside Eloquent, we use parameterized
     * queries to prevent SQL injection.
     *
     * @param string $searchTerm User-provided search term (potentially malicious)
     * @param string $status Donation status filter
     * @return array Matching donations
     */
    public function findDonationsWithPDO(string $searchTerm, string $status = 'available'): array
    {
        // SECURE: Using PDO prepared statements with named parameter binding
        // The :searchTerm and :status placeholders are safely bound by PDO,
        // preventing any SQL injection even if $searchTerm contains malicious SQL.
        $results = DB::select(
            'SELECT d.id, d.title, d.description, d.quantity, d.unit, d.expiry_date, d.status,
                    u.name as donor_name, u.organization_name
             FROM donations d
             INNER JOIN users u ON d.user_id = u.id
             WHERE d.status = :status
             AND d.expiry_date > NOW()
             AND (d.title LIKE :searchTerm OR d.description LIKE :searchTerm2)
             ORDER BY d.created_at DESC
             LIMIT 50',
            [
                'status' => $status,             // PDO bound parameter
                'searchTerm' => '%' . $searchTerm . '%',   // PDO bound parameter
                'searchTerm2' => '%' . $searchTerm . '%',  // PDO bound parameter
            ]
        );

        return $results;
    }

    /**
     * Get donation statistics using Eloquent's aggregate methods.
     * All aggregations use parameterized queries internally.
     */
    public function getStatistics(): array
    {
        return [
            'total_donations' => Donation::count(),
            'active_donations' => Donation::where('status', 'available')->count(),
            'claimed_donations' => Donation::where('status', 'claimed')->count(),
            'collected_donations' => Donation::where('status', 'collected')->count(),
            'total_quantity_donated' => Donation::sum('quantity'),
        ];
    }
}
