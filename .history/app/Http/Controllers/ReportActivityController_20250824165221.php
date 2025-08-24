public function debugStorage()
    {
        $reports = ReportActivity::whereNotNull('evidence')->get();
        $debug = [];

        foreach ($reports as $report) {
            $debug[] = [
                'id' => $report->id,
                'evidence_path' => $report->evidence,
                'file_exists_public' => Storage::disk('public')->exists($report->evidence),
                'file_exists_storage' => file_exists(storage_path('app/public/' . $report->evidence)),
                'full_path' => storage_path('app/public/' . $report->evidence),
                'public_url' => asset('storage/' . $report->evidence),
                'storage_link_exists' => is_link(public_path('storage'))
            ];
        }

        $info = [
            'storage_path' => storage_path('app/public'),
            'public_path' => public_path('storage'),
            'storage_link_exists' => is_link(public_path('storage')),
            'storage_link_target' => is_link(public_path('storage')) ? readlink(public_path('storage')) : 'Not a symlink',
            'reports_with_evidence' => $reports->count(),
            'debug_data' => $debug
        ];

        return response()->json($info, 200, [], JSON_PRETTY_PRINT);
    }

    // Method untuk fix storage link
    public function fixStorage()
    {
        try {
            // Hapus storage link lama jika ada
            if (is_link(public_path('storage'))) {
                unlink(public_path('storage'));
            }

            // Buat ulang storage link
            \Artisan::call('storage:link');

            return redirect()->back()->with('success', 'Storage link berhasil diperbaiki!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbaiki storage link: ' . $e->getMessage());
        }
    }

    // Method untuk clean up file yang tidak ada
    public function cleanupMissingFiles()
    {
        $reports = ReportActivity::whereNotNull('evidence')->get();
        $cleaned = 0;

        foreach ($reports as $report) {
            if (!Storage::disk('public')->exists($report->evidence)) {
                $report->update(['evidence' => null]);
                $cleaned++;
            }
        }

        return redirect()->back()->with('success', "Berhasil membersihkan {$cleaned} file yang hilang dari database.");
    }
