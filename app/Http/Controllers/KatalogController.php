<?php

namespace App\Http\Controllers;

use App\Models\ChildsCategoriesKatalog;
use App\Models\GrandChildsCategoriesKatalog;
use App\Models\ParentsCategoriesKatalog;
use App\Models\PhotoKatalog;
use Illuminate\Http\Request;

class KatalogController extends Controller
{

    public function index()
    {
        $data = ParentsCategoriesKatalog::all();

        return view('katalog.index', compact('data'));
    }

    public function storeChild(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $exists = ChildsCategoriesKatalog::where('name', $request->name)->exists();
            if ($exists) {
                return redirect()->back()->with('error', 'Kategori sudah ada!');
            }

            $dataPost = $request->only(['name', 'description']);
            $dataPost['parents_id'] = $request->parent_id;

            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $filename = 'thumbnail_' . time() . rand(1, 9999) . '.' . $file->getClientOriginalExtension();
                $destinationPath = 'uploads/file/thumbnail';
                $file->move($destinationPath, $filename);
                $dataPost['thumbnail'] = $destinationPath . '/' . $filename;
            }

            $newCategory = ChildsCategoriesKatalog::create($dataPost);

            return redirect()->route('katalog.detail', $newCategory->id)->with('success', 'Berhasil disimpan!');
        } catch (\Exception $e) {
            \Log::error("Error occurred while storing the data: " . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function storeGrandChild(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $exists = GrandChildsCategoriesKatalog::where('name', $request->name)->exists();
            if ($exists) {
                return redirect()->back()->with('error', 'Kategori sudah ada!');
            }

            $dataPost = $request->only(['name', 'description']);
            $dataPost['childs_id'] = $request->childs_id;

            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $filename = 'thumbnail_' . time() . rand(1, 9999) . '.' . $file->getClientOriginalExtension();
                $destinationPath = 'uploads/file/thumbnail';
                $file->move(public_path($destinationPath), $filename);
                $dataPost['thumbnail'] = $destinationPath . '/' . $filename;
            }

            $newCategory = GrandChildsCategoriesKatalog::create($dataPost);

            return redirect()->route('katalog.detail', $newCategory->id)->with('success', 'Berhasil disimpan!');
        } catch (\Exception $e) {
            \Log::error("Error occurred while storing the data: " . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function show($id)
    {
        $parents = ParentsCategoriesKatalog::where('id', $id)->with('childs')->firstOrFail();

        return view('katalog.show', compact('parents'));
    }

    public function detail($parentId, $childId)
    {

        $childs = ChildsCategoriesKatalog::where('id', $childId)
            ->with('grand_childs')
            ->firstOrFail();

        return view('katalog.detail', compact('childs'));
    }

    public function getParentDetail($parentId)
    {
        $parent = ParentsCategoriesKatalog::where('id', $parentId)
            ->with('photos')
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $parent,
        ]);
    }

    public function childDetail($childId)
    {
        $child = ChildsCategoriesKatalog::with('photos')
            ->findOrFail($childId);

        return view('katalog.detail', compact('child'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'thumbnail' => 'nullable|image',
            'photo.*' => 'nullable|image',
        ]);

        $parent = ParentsCategoriesKatalog::findOrFail($request->id);
        $parent->name = $request->name;
        $parent->description = $request->description;

        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails');
            $parent->thumbnail = $thumbnailPath;
        }

        $parent->save();

        return redirect()->back()->with('success', 'Data updated successfully');
    }

}