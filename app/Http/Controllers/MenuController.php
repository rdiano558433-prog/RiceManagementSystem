<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Display a listing of the menu items
     */
    public function index()
    {
        $menus = Menu::all();
        return view('menu.index', compact('menus'));
    }

    /**
     * Show the form for creating a new menu item
     */
    public function create()
    {
        return view('menu.create');
    }

    /**
     * Store a newly created menu item
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:menus',
            'category' => 'required|string|max:255',
            'price_per_kilo' => 'required|numeric|min:0.01',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string|max:1000',
        ]);

        Menu::create($validated);

        return redirect()->route('menu.index')->with('success', 'Rice product added successfully.');
    }

    /**
     * Show the form for editing the specified menu item
     */
    public function edit(Menu $menu)
    {
        return view('menu.edit', compact('menu'));
    }

    /**
     * Update the specified menu item
     */
    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:menus,name,' . $menu->id,
            'category' => 'required|string|max:255',
            'price_per_kilo' => 'required|numeric|min:0.01',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string|max:1000',
        ]);

        $menu->update($validated);

        return redirect()->route('menu.index')->with('success', 'Rice product updated successfully.');
    }

    /**
     * Delete the specified menu item
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();

        return redirect()->route('menu.index')->with('success', 'Rice product deleted successfully.');
    }
}