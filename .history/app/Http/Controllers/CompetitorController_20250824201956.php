<?php

namespace App\Http\Controllers;

use App\Models\Competitor;
use Illuminate\Http\Request;

class CompetitorController extends Controller
{
    // 🔹 READ (Index)
    public function index()
    {
         $query = Competitor::query(Request $request);

        if ($request->has('cluster') && $request->cluster != '') {
            $query->where('cluster', $request->cluster);
        }
            $competitors = Competitor::latest()->get();
            return view('report.competitor', compact('competitors'));
        }

    // 🔹 CREATE (Store)
    public function store(Request $request)
    {
        $request->validate([
            'cluster' => 'required|string',
            'competitor_name' => 'required|array',
            'harga' => 'required|array',
        ]);

        foreach ($request->competitor_name as $key => $name) {
            Competitor::create([
                'cluster' => $request->cluster,
                'competitor_name' => $name,
                'harga' => $request->harga[$key],
            ]);
        }

        return redirect()->route('competitor.index')->with('success', 'Data competitor berhasil ditambahkan');
    }

    // 🔹 EDIT (Form Edit)
    public function edit($id)
    {
        $competitor = Competitor::findOrFail($id);
        return view('competitor_edit', compact('competitor'));
    }

    // 🔹 UPDATE
    public function update(Request $request, $id)
    {
        $request->validate([
            'cluster' => 'required|string',
            'competitor_name' => 'required|string',
            'harga' => 'required|numeric',
        ]);

        $competitor = Competitor::findOrFail($id);
        $competitor->update($request->all());

        return redirect()->route('competitor.index')->with('success', 'Data competitor berhasil diperbarui');
    }

    // 🔹 DELETE
    public function destroy($id)
    {
        $competitor = Competitor::findOrFail($id);
        $competitor->delete();

        return redirect()->route('competitor.index')->with('success', 'Data competitor berhasil dihapus');
    }
}
