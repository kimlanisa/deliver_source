<?php

namespace App\Http\Controllers;

use App\Models\ChildsCategoriesKatalog;
use App\Models\FilePhoto;
use App\Models\GrandChildsCategoriesKatalog;
use App\Models\ParentsCategoriesKatalog;
use App\Models\PhotoKatalog;
use App\Models\Variasi;
use Facade\FlareClient\Stacktrace\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KatalogController extends Controller
{

    public function index()
    {
        $data = ParentsCategoriesKatalog::all();

        $photo = PhotoKatalog::with('files')->get();

        return view('katalog.index', compact('data', 'photo'));
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

    // public function storePhoto(Request $request)
    // {
    //     try {
    //         $request->validate([
    //             'name' => 'required|string|max:255',
    //             'description' => 'nullable|string|max:1000',
    //             'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    //             'variasi' => 'nullable|string|max:255',
    //             'link_url' => 'nullable|string|max:255',
    //         ]);

    //         $dataPost = $request->only(['name', 'description', 'link_url']);

    //         if ($request->filled('parents_id')) {
    //             $dataPost['parents_id'] = $request->parents_id;
    //         }

    //         if ($request->filled('childs_id')) {
    //             $dataPost['childs_id'] = $request->childs_id;
    //         }

    //         if ($request->filled('grand_childs_id')) {
    //             $dataPost['grand_childs_id'] = $request->grand_childs_id;
    //         }

    //         if ($request->hasFile('thumbnail')) {
    //             $file = $request->file('thumbnail');
    //             $filename = 'thumbnail_' . time() . rand(1, 9999) . '.' . $file->getClientOriginalExtension();
    //             $destinationPath = 'uploads/file/thumbnail';
    //             $file->move($destinationPath, $filename);
    //             $dataPost['thumbnail'] = $destinationPath . '/' . $filename;
    //         }

    //         if ($request->filled('variasi')) {
    //             $variasiArray = explode(',', $request->input('variasi'));
    //             $variasiArray = array_map('trim', $variasiArray);
    //             $dataPost['variasi'] = implode(', ', $variasiArray);
    //         }

    //         // dd($dataPost);

    //         \Log::info("Data yang akan disimpan: ", $dataPost);
    //         $result = PhotoKatalog::create($dataPost);
    //         \Log::info("Data berhasil disimpan: ", $result->toArray());

    //         return redirect()->to(url()->previous())->with('success', $dataPost['name'] . ' berhasil disimpan!');
    //     } catch (\Exception $e) {
    //         \Log::error("Error occurred while storing the data: " . $e->getMessage());
    //         return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
    //     }
    // }

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

        $dataPhotos = PhotoKatalog::where('parents_id', $id)->get();

        $photos = FilePhoto::whereIn('photo_id', $dataPhotos->pluck('id'))->get();

        return view('katalog.show', compact('parents', 'photos', 'dataPhotos'));
    }

    public function detail($parentId, $childId)
    {
        $parents = ParentsCategoriesKatalog::where('id', $parentId)
            ->with('childs')
            ->firstOrFail();

        $childs = ChildsCategoriesKatalog::where('id', $childId)
            ->with('grand_childs')
            ->firstOrFail();

        $dataPhotos = PhotoKatalog::where('childs_id', $childId)->get();

        $photos = FilePhoto::whereIn('photo_id', $dataPhotos->pluck('id'))->get();

        return view('katalog.detail', compact('childs', 'parents', 'photos', 'dataPhotos'));
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

    public function destroyPhoto($photoId)
    {
        try {
            $photo = PhotoKatalog::findOrFail($photoId);

            $files = $photo->files;

            foreach ($files as $file) {
                $filePath = public_path($file->file_path);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            $photo->delete();

            return response()->json(['success' => true, 'message' => 'Photo deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete photo.'], 500);
        }
    }

    public function destroyPhotoDropzone($fileId)
    {
        try {
            $file = FilePhoto::findOrFail($fileId);

            // $filePath = public_path($file->file_path);
            // if (file_exists($filePath)) {
            //     unlink($filePath);
            // }

            $file->delete();

            return response()->json(['success' => true, 'message' => 'File deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete file.'], 500);
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
        $photo = PhotoKatalog::with('files')->findOrFail($photoId);

        $files = $photo->files;

        $thumbnailImage = $files->first();

        $mainImages = $files->slice(1);

        $variasi = Variasi::where('photo_id', $photoId)->get();

        return view('katalog.photo_detail', compact('photo', 'thumbnailImage', 'mainImages', 'variasi'));
    }

    public function storeMedia(Request $request)
    {
        $request->validate([
            'file_name.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $fileData = [];

            if ($request->hasFile('file_name')) {
                foreach ($request->file('file_name') as $file) {
                    $filename = 'photo_' . time() . rand(1, 9999) . '.' . $file->getClientOriginalExtension();
                    $destinationPath = 'uploads/file/photos';
                    $file->move(public_path($destinationPath), $filename);

                    $fileData[] = [
                        'file_name' => $filename,
                        'file_path' => $destinationPath . '/' . $filename,
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'files' => $fileData,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error uploading photos: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function storeDetailPhoto(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'variasi' => 'required|array',
            'variasi.*' => 'string|max:255',
            'description' => 'required|string|max:255',
            'link_url' => 'required|string',
            'file_name.*' => 'required|string',
        ]);

        $photoData = [
            'name' => $request->name,
            'description' => $request->description,
            'link_url' => $request->link_url,
        ];

        if ($request->filled('parents_id')) {
            $photoData['parents_id'] = $request->parents_id;
        }

        if ($request->filled('childs_id')) {
            $photoData['childs_id'] = $request->childs_id;
        }

        if ($request->filled('grand_childs_id')) {
            $photoData['grand_childs_id'] = $request->grand_childs_id;
        }

        $photo = PhotoKatalog::create($photoData);

        foreach ($request->variasi as $variasi) {
            Variasi::create([
                'photo_id' => $photo->id,
                'name' => $variasi,
            ]);
        }

        $fileNames = $request->input('file_name', []);
        foreach ($fileNames as $fileName) {
            FilePhoto::create([
                'photo_id' => $photo->id,
                'file_name' => $fileName,
                'file_path' => 'uploads/file/photos/' . $fileName,
            ]);
        }

        return redirect()->to(url()->previous())->with('success', 'Berhasil disimpan!');
    }

    public function deleteMedia(Request $request)
    {
        $fileName = $request->input('file_name');
        $filePath = public_path('uploads/file/photos/' . $fileName);

        if (file_exists($filePath)) {
            unlink($filePath);

            FilePhoto::where('file_name', $fileName)->delete();

            return response()->json(['success' => true, 'message' => 'File deleted successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'File not found.'], 404);
    }

    public function fetchPhotoData($photoId)
    {
        try {
            $photo = PhotoKatalog::with(['files', 'variasi'])->findOrFail($photoId);

            $responseData = [
                'id' => $photo->id,
                'name' => $photo->name,
                'description' => $photo->description,
                'link_url' => $photo->link_url,
                'variasi' => $photo->variasi->map(function ($variasi) {
                    return [
                        'id' => $variasi->id,
                        'name' => $variasi->name,
                    ];
                }),
                'files' => $photo->files->map(function ($file) {
                    return [
                        'id' => $file->id,
                        'file_name' => $file->file_name,
                        'file_path' => asset($file->file_path),
                    ];
                }),
            ];

            return response()->json([
                'success' => true,
                'data' => $responseData,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch photo data.',
            ], 500);
        }
    }

    public function updateDetailPhoto(Request $request, $photoId)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'link_url' => 'required|string',
                'variasi.*' => 'nullable|string|max:255', // Validasi untuk variasi
                'file_name.*' => 'nullable|string',
            ]);

            $photoData = [
                'name' => $request->name,
                'description' => $request->description,
                'link_url' => $request->link_url,
            ];

            $photo = PhotoKatalog::findOrFail($photoId);
            $photo->update($photoData);

            // Update variasi yang ada
            if ($request->has('variasi')) {
                foreach ($request->variasi as $variasi) {
                    if (!empty($variasi)) {
                        // Tambahkan variasi baru jika tidak ada
                        $photo->variasi()->updateOrCreate(
                            ['name' => $variasi],
                            ['name' => $variasi]
                        );
                    }
                }
            }

            // Proses file yang di-upload
            $existingFiles = $photo->files->pluck('file_name')->toArray();
            $fileNames = $request->input('file_name', []);
            foreach ($fileNames as $fileName) {
                if (!in_array($fileName, $existingFiles)) {
                    FilePhoto::create([
                        'photo_id' => $photo->id,
                        'file_name' => $fileName,
                        'file_path' => 'uploads/file/photos/' . $fileName,
                    ]);
                }
            }

            return redirect()->to(url()->previous())->with('success', 'Berhasil di update');
        } catch (\Exception $e) {
            Log::error("Error occurred while updating the data: " . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengupdate data.');
        }
    }

    public function destroyVariation($variasiId)
    {
        try {
            $variasi = Variasi::findOrFail($variasiId);
            $variasi->delete();

            return response()->json(['success' => true, 'message' => 'Variasi deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete variasi.'], 500);
        }
    }

}