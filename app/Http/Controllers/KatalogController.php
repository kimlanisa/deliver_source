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

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $dataPost = $request->only(['name', 'description']);
        $dataPost['parents_id'] = $request->parent_id;

        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = 'thumbnail_' . time() . rand(1, 9999) . '.' . $file->getClientOriginalExtension();
            $destinationPath = 'uploads/file/thumbnail';
            $file->move($destinationPath, $filename);
            $dataPost['thumbnail'] = $destinationPath . '/' . $filename;
        }

        $newCategory = ParentsCategoriesKatalog::create($dataPost);

        return redirect()->route('katalog.show', $newCategory->id)->with('success', $dataPost['name'] . ' berhasil disimpan!');
    }

    public function update(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $dataPost = $request->only(['name', 'description']);
            $dataPost['parents_id'] = $request->parent_id;

            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $filename = 'thumbnail_' . time() . rand(1, 9999) . '.' . $file->getClientOriginalExtension();
                $destinationPath = 'uploads/file/thumbnail';
                $file->move($destinationPath, $filename);
                $dataPost['thumbnail'] = $destinationPath . '/' . $filename;
            }

            $parent = ParentsCategoriesKatalog::findOrFail($request->id);
            $parent->update($dataPost);

            return redirect()->to(url()->previous())->with('success', 'Berhasil disimpan!');
        } catch (\Exception $e) {
            \Log::error("Error occurred while storing the data: " . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function storeChild(Request $request)
    {
        // try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $dataPost = $request->only(['name', 'description']);
            $dataPost['parents_id'] = $request->parents_id;

            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $filename = 'thumbnail_' . time() . rand(1, 9999) . '.' . $file->getClientOriginalExtension();
                $destinationPath = 'uploads/file/thumbnail';
                $file->move($destinationPath, $filename);
                $dataPost['thumbnail'] = $destinationPath . '/' . $filename;
            }

            $newCategory = ChildsCategoriesKatalog::create($dataPost);

            return redirect()->route('katalog.detail', [
                'parentId' => $request->parents_id,
                'childId' => $newCategory->id,
            ])->with('success', $dataPost['name'] . ' berhasil disimpan!');
        // } catch (\Exception $e) {
            // \Log::error("Error occurred while storing the data: " . $e->getMessage());
            // return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        // }
    }

    public function updateChild(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $dataPost = $request->only(['name', 'description']);

            if ($request->filled('parents_id')) {
                $dataPost['parents_id'] = $request->parents_id;
            } elseif ($request->filled('childs_id')) {
                $dataPost['childs_id'] = $request->childs_id;
            } elseif ($request->filled('grand_childs_id')) {
                $dataPost['grand_childs_id'] = $request->grand_childs_id;
            }

            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $filename = 'thumbnail_' . time() . rand(1, 9999) . '.' . $file->getClientOriginalExtension();
                $destinationPath = 'uploads/file/thumbnail';
                $file->move($destinationPath, $filename);
                $dataPost['thumbnail'] = $destinationPath . '/' . $filename;
            }

            $child = ChildsCategoriesKatalog::findOrFail($request->id);
            $child->update($dataPost);

            return redirect()->to(url()->previous())->with('success', 'Berhasil disimpan!');
        } catch (\Exception $e) {
            \Log::error("Error occurred while storing the data: " . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function storePhoto(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'variasi' => 'nullable|string|max:255',
                'link_url' => 'nullable|string|max:255',
            ]);

            $dataPost = $request->only(['name', 'description', 'link_url']);

            if ($request->filled('parents_id')) {
                $dataPost['parents_id'] = $request->parents_id;
            }

            if ($request->filled('childs_id')) {
                $dataPost['childs_id'] = $request->childs_id;
            }

            if ($request->filled('grand_childs_id')) {
                $dataPost['grand_childs_id'] = $request->grand_childs_id;
            }

            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $filename = 'thumbnail_' . time() . rand(1, 9999) . '.' . $file->getClientOriginalExtension();
                $destinationPath = 'uploads/file/thumbnail';
                $file->move($destinationPath, $filename);
                $dataPost['thumbnail'] = $destinationPath . '/' . $filename;
            }

            if ($request->filled('variasi')) {
                $variasiArray = explode(',', $request->input('variasi'));
                $variasiArray = array_map('trim', $variasiArray);
                $dataPost['variasi'] = implode(', ', $variasiArray);
            }

            // dd($dataPost);

            \Log::info("Data yang akan disimpan: ", $dataPost);
            $result = PhotoKatalog::create($dataPost);
            \Log::info("Data berhasil disimpan: ", $result->toArray());

            return redirect()->to(url()->previous())->with('success', $dataPost['name'] . ' berhasil disimpan!');
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

    public function updateGrandChild(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $dataPost = $request->only(['name', 'description']);

            if ($request->filled('parents_id')) {
                $dataPost['parents_id'] = $request->parents_id;
            } elseif ($request->filled('childs_id')) {
                $dataPost['childs_id'] = $request->childs_id;
            } elseif ($request->filled('grand_childs_id')) {
                $dataPost['grand_childs_id'] = $request->grand_childs_id;
            }

            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $filename = 'thumbnail_' . time() . rand(1, 9999) . '.' . $file->getClientOriginalExtension();
                $destinationPath = 'uploads/file/thumbnail';
                $file->move($destinationPath, $filename);
                $dataPost['thumbnail'] = $destinationPath . '/' . $filename;
            }

            $grandChild = GrandChildsCategoriesKatalog::findOrFail($request->id);
            $grandChild->update($dataPost);

            return redirect()->to(url()->previous())->with('success', 'Berhasil disimpan!');
        } catch (\Exception $e) {
            \Log::error("Error occurred while storing the data: " . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function show($id)
    {
        $parents = ParentsCategoriesKatalog::where('id', $id)->with('childs')->firstOrFail();

        $photos = PhotoKatalog::where('parents_id', $id)->get();

        return view('katalog.show', compact('parents', 'photos'));
    }

    public function detail($parentId, $childId)
    {
        $parents = ParentsCategoriesKatalog::where('id', $parentId)
            ->with('childs')
            ->firstOrFail();

        $childs = ChildsCategoriesKatalog::where('id', $childId)
            ->with('grand_childs')
            ->firstOrFail();

        $photos = PhotoKatalog::where('childs_id', $childId)->get();

        return view('katalog.detail', compact('childs', 'parents', 'photos'));
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

    public function updatePhoto(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $dataPost = $request->only(['name', 'description', 'link_url']);

            if ($request->filled('parents_id')) {
                $dataPost['parents_id'] = $request->parents_id;
            }

            if ($request->filled('childs_id')) {
                $dataPost['childs_id'] = $request->childs_id;
            }

            if ($request->filled('grand_childs_id')) {
                $dataPost['grand_childs_id'] = $request->grand_childs_id;
            }

            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $filename = 'thumbnail_' . time() . rand(1, 9999) . '.' . $file->getClientOriginalExtension();
                $destinationPath = 'uploads/file/thumbnail';
                $file->move($destinationPath, $filename);
                $dataPost['thumbnail'] = $destinationPath . '/' . $filename;
            }

            $photo = PhotoKatalog::findOrFail($request->id);
            $photo->update($dataPost);

            return redirect()->to(url()->previous())->with('success', $dataPost['name'] . ' berhasil diupdate!');
        } catch (\Exception $e) {
            \Log::error("Error occurred while storing the data: " . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function destroy($id)
    {
        try {
            $parent = ParentsCategoriesKatalog::findOrFail($id);
            $parent->delete();

            return redirect()->to(url()->previous())->with('success', 'Berhasil dihapus!');
        } catch (\Exception $e) {
            \Log::error("Error occurred while deleting the data: " . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }

    public function destroyPhoto($id)
    {
        try {
            $photo = PhotoKatalog::findOrFail($id);
            $photo->delete();

            return redirect()->to(url()->previous())->with('success', 'Berhasil dihapus!');
        } catch (\Exception $e) {
            \Log::error("Error occurred while deleting the data: " . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }

    public function destroyChild($childId)
    {
        try {
            $child = ChildsCategoriesKatalog::findOrFail($childId);
            $child->delete();

            return redirect()->to(url()->previous())->with('success', 'Berhasil dihapus!');
        } catch (\Exception $e) {
            \Log::error("Error occurred while deleting the data: " . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }

    public function destroyGrandChild($grandChildId)
    {
        try {
            $grandChild = GrandChildsCategoriesKatalog::findOrFail($grandChildId);
            $grandChild->delete();

            return redirect()->to(url()->previous())->with('success', 'Berhasil dihapus!');
        } catch (\Exception $e) {
            \Log::error("Error occurred while deleting the data: " . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }

    public function photoDetail($photoId)
    {
        $photo = PhotoKatalog::findOrFail($photoId);

        if (!is_null($photo->grand_childs_id)) {
            $mainImage = GrandChildsCategoriesKatalog::where('id', $photo->grand_childs_id)->first();
        } elseif (!is_null($photo->childs_id)) {
            $mainImage = ChildsCategoriesKatalog::where('id', $photo->childs_id)->first();
        } elseif (!is_null($photo->parents_id)) {
            $mainImage = ParentsCategoriesKatalog::where('id', $photo->parents_id)->first();
        }

        return view('katalog.photo_detail', compact('photo', 'mainImage'));
    }

}