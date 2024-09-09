<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Shop;
use App\Models\Retur;
use App\Models\CustomMenu;
use Illuminate\Http\Request;
use App\Models\KartuStockMenu;
use Illuminate\Support\Carbon;
use App\Models\CustomMenuLabel;
use App\Models\CustomMenuValue;
use Yajra\DataTables\DataTables;
use App\Models\InboundReturDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Validator;

class CustomMenuController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!canPermission('Daftar Menu Kustom'))
            return abort(404);

        return view('custom_menu.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!canPermission('Daftar Menu Kustom.Create'))
            return abort(404);

        return view('custom_menu.create-update');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id = $request->id;
        $input = $request->all();


        if (!canPermission('Daftar Menu Kustom.Edit') && $id)
            return abort(404);
        else if (!canPermission('Daftar Menu Kustom.Create') && !$id)
            return abort(404);

        $validator = Validator::make($input, [
            'nama_menu' => $request->id ? 'required|unique:custom_menus,name,' . $id : 'required|unique:custom_menus,name',
            'label' => 'required',
            'type_input' => 'required',
        ]);

        $generate_data = $this->generateData($request);
        $label_type_input = $generate_data['label_type_input'] ?? [];
        $label_show = $generate_data['label_show'] ?? [];
        $field_table = $generate_data['field_table'] ?? '';
        $select_option = $generate_data['select_option'] ?? [];

        if ($validator->fails()) {
            $input['label_type_input'] = $label_type_input;
            $input['label_show'] = $label_show;
            $input['select_option'] = $select_option;
            return back()->withErrors($validator)->withInput($input);
        }

        $data_custom_menu = null;
        if ($id) {
            $data_custom_menu = CustomMenu::find($id);
            if (!$data_custom_menu)
                return back()->with('error', 'Data tidak ditemukan!')->withInput($input);
        }

        $input['label_type_input'] = json_encode($label_type_input);
        $input['select_option'] = json_encode($select_option);
        $input['label_show'] = json_encode($label_show);
        $input['slug'] = strtolower(str_replace(' ', '-', $request->nama_menu));

        try {

            DB::transaction(function () use ($id, $input, $request, $data_custom_menu, $field_table, $label_type_input) {
                $input['name'] = $request->nama_menu;
                $input['created_by'] = Auth::user()->id;

                $table_name = 'cstmmenu_' . strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $request->nama_menu));
                $input['table_name'] = $table_name;

                if ($data_custom_menu) {
                    $old_table_name = $data_custom_menu->table_name;
                    if (Schema::hasTable($old_table_name)) {
                        if ($old_table_name !== $table_name && !Schema::hasTable($table_name)) {
                            DB::statement('RENAME TABLE ' . $old_table_name . ' TO ' . $table_name);
                        }

                        $input_label_type = $label_type_input;
                        $old_label_type = json_decode($data_custom_menu->label_type_input, true);

                        $new_field_collection = [];
                        foreach ($input_label_type as $key => $item) {
                            $field_name = $item['name'];
                            $old_field = array_filter($old_label_type, function ($old_item) use ($item) {
                                return $old_item['name'] === $item['old_name'] ?? '';
                            })[0] ?? null;

                            // $old_field = $old_label_type[$key] ?? '';

                            if (!$old_field) {
                                if (!Schema::hasColumn($table_name, $field_name)) {
                                    DB::statement("ALTER TABLE {$table_name} ADD COLUMN {$field_name} LONGTEXT NULL;");
                                }
                            } else if ($old_field['name'] !== $field_name) {
                                if (Schema::hasColumn($table_name, $old_field['name']) && !Schema::hasColumn($table_name, $field_name)) {
                                    DB::statement("ALTER TABLE {$table_name} RENAME COLUMN {$old_field['name']} TO {$field_name};");
                                }
                            }

                            // if (!$old_field)
                            //     DB::statement("ALTER TABLE {$table_name} ADD COLUMN {$field_name} LONGTEXT NULL;");
                            // else if ($old_field['id'] === $item['id'] && $old_field['name'] !== $field_name) {
                            //     if (Schema::hasColumn($table_name, $old_field['name'])) {
                            //         DB::statement("ALTER TABLE {$table_name} RENAME COLUMN {$old_field['name']} TO {$field_name};");
                            //     }
                            // }

                            $new_field_collection[] = $item;
                        }

                        foreach ($old_label_type as $key => $item) {
                            if (!array_filter($new_field_collection, function ($new_item) use ($item) {
                                return $new_item['name'] === $item['name'];
                            })) {
                                $field_name = $item['name'];
                                if (Schema::hasColumn($table_name, $field_name)) {
                                    DB::statement("ALTER TABLE {$table_name} DROP COLUMN {$field_name};");
                                }
                            }
                        }

                        $old_data_permission = [
                            "{$data_custom_menu->permission_name}",
                            "{$data_custom_menu->permission_name}.Create",
                            "{$data_custom_menu->permission_name}.Edit",
                            "{$data_custom_menu->permission_name}.Delete"
                        ];

                        $permission_name = preg_replace('/[^a-zA-Z0-9]/', ' ', $request->nama_menu);
                        $data_permission = [
                            "{$permission_name}",
                            "{$permission_name}.Create",
                            "{$permission_name}.Edit",
                            "{$permission_name}.Delete"
                        ];

                        foreach ($data_permission as $key => $itemprms) {
                            $permission = Permission::where('name', $old_data_permission[$key])->first();
                            if ($permission) {
                                DB::table('permissions')
                                    ->where('id', $permission->id)
                                    ->update(['name' => $itemprms]);
                            } else {
                                Permission::create(['name' => $itemprms]);
                            }
                        }

                        $data_custom_menu->update($input);
                    } else {
                        throw new \Exception('Tabel tidak ditemukan!');
                    }
                } else {

                    $custom_menu = CustomMenu::create($input);

                    $custom_menu_label_data = [];
                    foreach ($label_type_input as $key => $item) {
                        $custom_menu_label_data[] = [
                            'custom_menu_id' => $custom_menu->id,
                            'label' => $item['label'],
                            'name' => $item['name'],
                            'type_input' => $item['type_input'],
                            'show_data' => $item['show_data'],
                        ];
                    }

                    CustomMenuLabel::insert($custom_menu_label_data);

                    $permission_name = preg_replace('/[^a-zA-Z0-9]/', ' ', $request->nama_menu);
                    $data_permission = [
                        "{$permission_name}",
                        "{$permission_name}.Create",
                        "{$permission_name}.Edit",
                        "{$permission_name}.Delete"
                    ];

                    foreach ($data_permission as $permission) {
                        Permission::create(['name' => $permission]);
                    }

                    if (!Schema::hasTable($table_name)) {
                        DB::statement("
                            CREATE TABLE " . $table_name . " (
                                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                " . $field_table . "
                                created_by BIGINT UNSIGNED NULL,
                                updated_by BIGINT UNSIGNED NULL,
                                created_at TIMESTAMP NULL,
                                updated_at TIMESTAMP NULL
                            )
                        ");
                    }
                }
            });

            return redirect(route('custom-menu.index'))->with('success', 'Data berhasil disimpan!');
        } catch (\Throwable $th) {

            return back()->with('error', 'Terjadi kesalahan, silahkan coba lagi!')->withInput($input);
        }
    }

    private function generateData($request)
    {
        if (count($request->label) < 1)
            return [];

        try {
            $field_table = '';

            $label_type_input = [];
            $all_select_option = [];
            foreach ($request->label as $key_label => $item) {

                $show_data = array_filter($request->label_show ?? [], function ($item_sd) use ($request, $key_label) {
                    $item_sd = explode(' ', $item_sd)[1] ?? '';
                    return $key_label === intval($item_sd);
                }, ARRAY_FILTER_USE_BOTH) ?? [];

                $select_option = [];
                if ($request->type_input[$key_label] === 'select') {
                    foreach ($request->pilihan_id[$key_label] as $key_option => $item_option) {
                        $select_option[] = [
                            'id' => $key_option,
                            'label' => $request->pilihan[$key_label][$key_option] ?? '',
                            'color' => $request->color_pilihan[$key_label][$key_option] ?? '',
                        ];
                    }
                }

                if(count($select_option) > 0) {
                    $all_select_option[] = $select_option;
                }


                $result = [
                    'id' => $key_label,
                    'label' => $item,
                    'type_input' => $request->type_input[$key_label],
                    'name' =>  preg_replace('/[^a-zA-Z0-9]/', '', strtolower($item)),
                    'show_data' => count($show_data) > 0 ? true : false,
                    'select_option' => $select_option,
                    'editable' => $request->editable_pilihan[$key_label] ?? false,
                    'filter' => $request->filter_pilihan[$key_label] ?? false,
                    'old_name' => preg_replace('/[^a-zA-Z0-9]/', '', strtolower($request->old_label[$key_label] ?? '')),
                ];
                $field_name =  preg_replace('/[^a-zA-Z0-9]/', '', strtolower($item));
                $field_table .=  "{$field_name} LONGTEXT NULL,";

                $label_type_input[] = $result;
            }

            $label_show = array_map(function ($item) {
                return [
                    'label' => $item
                ];
            }, $request->label_show ?? []);

            return [
                'label_type_input' => $label_type_input,
                'label_show' => $label_show,
                'field_table' => $field_table,
                'select_option' => $all_select_option
            ];
        } catch (\Throwable $th) {
            return [];
        }
    }

    public function edit($id)
    {
        if (!canPermission('Daftar Menu Kustom.Edit'))
            return abort(404);

        $data = CustomMenu::findOrFail($id);
        $data->label_type_input = json_decode($data->label_type_input, true);
        $data->label_show = json_decode($data->label_show, true);

        return view('custom_menu.create-update', compact('data'));
    }

    public function destroy(Request $request ,$id)
    {
        if (!canPermission('Daftar Menu Kustom.Delete'))
            return abort(404);

        $validasi = Validator::make($request->all(), [
            'password' => 'required'
        ]);

        if ($validasi->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validasi->errors()->first()
            ]);
        }


        if(!Hash::check($request->password, Auth::user()->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Password tidak sesuai!'
            ]);
        }


        $custom_menu = CustomMenu::find($id);
        if (!$custom_menu)
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan!'
            ]);

        if (Schema::hasTable($custom_menu->table_name)) {
            Schema::dropIfExists($custom_menu->table_name);
        }

        $data_permission = [
            "{$custom_menu->permission_name}",
            "{$custom_menu->permission_name}.Create",
            "{$custom_menu->permission_name}.Edit",
            "{$custom_menu->permission_name}.Delete"
        ];

        foreach ($data_permission as $permission) {
            $permission = Permission::where('name', $permission)->first();
            if ($permission) {
                $permission->delete();
            }
        }

        $custom_menu->delete();
        return response()->json([
            'status' => true,
            'message' => 'Data berhasil dihapus!'
        ]);
    }

    public function dataTable(Request $request)
    {
        $data = CustomMenu::select(
            'id',
            'name',
            'created_by',
            'created_at',
            'updated_at'
        )->with(['createdBy:id,name']);

        if (empty($request->order[0]['column'])) {
            $data = $data->latest();
        }

        return DataTables::of($data)
            ->addindexColumn()
            ->addColumn('action', function ($data) {
                $actionButton = '';
                if (canPermission('Daftar Menu Kustom.Edit'))
                    $actionButton .= '<a href="javascript:void(0)" class="duplicate-menu-action dropdown-item" data-id="' . $data->id . '" data-name="' . $data->name . '">
                                        <i class="fa fa-fw fa-copy"></i>
                                        Duplikat Menu
                                    </a>';
                if (canPermission('Daftar Menu Kustom.Edit'))
                    $actionButton .= '<a href="' . route('custom-menu.edit', $data->id) . '" class="dropdown-item">
                                                            <i class="fa fa-fw fa-edit"></i>
                                                            Edit
                                                        </a>';

                if (canPermission('Daftar Menu Kustom.Delete'))
                    $actionButton .= ' <a href="javascript:void(0)" data-id="' . $data->id . '"
                                            class=" js-bs-tooltip-enabled btn-delete dropdown-item"
                                            data-bs-toggle="tooltip" title="Delete"><i
                                                class="fa fa-fw fa-trash-alt"></i>
                                            Hapus
                                        </a>';
                return '
                                        <div class="dropdown">
                                            <button
                                                class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled btn-print"
                                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                ' . $actionButton . '
                                            </ul>
                                        </div>
                                        ';
            })->rawColumns(['action',])
            ->smart(true)
            ->make(true);
    }

    public function menu($slug)
    {
        $data = CustomMenu::where('slug', $slug)->first();
        if (!$data)
            return abort(404);

        if (!canPermission("{$data->permission_name}"))
            return abort(404);

        $data = $this->generatedataCustomMenu($data);
        $list_shot_table = [];
        foreach ($data->label_show as $key => $item) {
            $list_shot_table[] = [
                'name' => $item['name'] ?? '',
                'data' => $item['label']
            ];
        }

        $data->list_show_table = $list_shot_table;

        return view('custom_menu.menu.index', compact('data'));
    }

    public function menuCreate($slug)
    {
        $data = CustomMenu::where('slug', $slug)->first();
        if (!$data)
            return abort(404);

        if (!canPermission("{$data->permission_name}.Create"))
            return abort(404);

        $data = $this->generatedataCustomMenu($data);

        return view('custom_menu.menu.create-update', compact('data'));
    }

    public function menuEdit($slug, $id)
    {
        $data = CustomMenu::where('slug', $slug)->first();
        if (!$data)
            return abort(404);

        if (!canPermission("{$data->permission_name}.Edit"))
            return abort(404);

        $data = $this->generatedataCustomMenu($data);

        $data_value = DB::table($data->table_name)->where('id', $id)->first();
        if (!$data_value)
            return abort(404);

        $data_value = collect((array) $data_value);

        return view('custom_menu.menu.create-update', compact('data', 'data_value'));
    }

    private function generatedataCustomMenu($data)
    {
        $data->label_data = json_decode($data->label_type_input, true);
        $list_label_show = json_decode($data->label_show, true);
        $label_show = [
            ...$data->label_data,
            [
                'label' => 'Updated By',
                'name' => 'updated_by',
                'show_data' => array_filter($list_label_show, function ($item) {
                    return $item['label'] === 'updated_by';
                }) ? true : false
            ],
            [
                'label' => 'Created By',
                'name' => 'created_by',
                'show_data' => array_filter($list_label_show, function ($item) {
                    return $item['label'] === 'created_by';
                }) ? true : false
            ],
            [
                'label' => 'Created At',
                'name' => 'created_at',
                'show_data' => array_filter($list_label_show, function ($item) {
                    return $item['label'] === 'created_at';
                }) ? true : false
            ],
            [
                'label' => 'Updated At',
                'name' => 'updated_at',
                'show_data' => array_filter($list_label_show, function ($item) {
                    return $item['label'] === 'updated_at';
                }) ? true : false
            ]
        ];

        $filter_label_show = [];
        foreach ($label_show as $key => $item) {
            if ($item['show_data'] === true)
                $filter_label_show[] = $item;
        }

        $data->label_show = $filter_label_show;

        return $data;
    }

    public function menuStore(Request $request, $slug)
    {

        $data = CustomMenu::where('slug', $slug)->first();
        if (!$data)
            return abort(404);

        $data = $this->generatedataCustomMenu($data);

        // $validasi = [];
        // foreach ($data->label_data as $key => $item) {
        //     $validasi[$item['name']] = 'required';
        // }

        // $request->validate($validasi);

        $input = $request->all();
        $id = $request->id;

        if (!canPermission("$data->permission_name.Edit") && $id)
            return abort(404);
        else if (!canPermission("$data->permission_name.Create") && !$id)
            return abort(404);

        // try {

            DB::transaction(function () use ($request, $data, $id, $input) {
                $input['custom_menu_id'] = $data->id;


                $data_input = [];
                foreach ($data->label_data as $key => $item) {
                    $data_input[$item['name']] = $request->{$item['name']};
                }

                $exists = null;
                if ($id) {
                    $exists = DB::table($data->table_name)
                        ->where('id', $id)
                        ->exists();
                }

                if ($exists && $id) {
                    $data_input['updated_by'] = Auth::user()->id;
                    $data_input['updated_at'] = now();
                    DB::table($data->table_name)
                        ->where('id', $id)
                        ->update($data_input);
                } else {
                    $data_input['created_by'] = Auth::user()->id;
                    $data_input['created_at'] = now();

                    DB::table($data->table_name)
                        ->insert($data_input);
                }
            });

            return redirect(route('custom-menu.menu', $data->slug))->with('success', 'Data berhasil disimpan!');
        // } catch (\Throwable $th) {

        //     return back()->with('error', 'Terjadi kesalahan, silahkan coba lagi!')->withInput($input);
        // }
    }

    public function menuDestroy($slug, $id)
    {
        $data = CustomMenu::where('slug', $slug)->first();
        if (!$data)
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan!'
            ]);

        if (!canPermission("{$data->permission_name}.Delete"))
            return abort(404);

        $exists = DB::table($data->table_name)
            ->where('id', $id)
            ->exists();

        if (!$exists)
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan!'
            ]);

        DB::table($data->table_name)
            ->where('id', $id)
            ->delete();

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil dihapus!'
        ]);
    }

    public function duplicateMenu(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:custom_menus,name,' . $id,
            'id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        $custom_menu = CustomMenu::find($id);
        if (!$custom_menu)
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan!'
            ]);

        try {

            DB::transaction(function () use ($custom_menu, $request) {
                $data = $custom_menu->toArray();
                unset($data['id']);
                unset($data['created_at']);
                unset($data['updated_at']);
                $data['name'] = $request->name;
                $data['slug'] = strtolower(str_replace(' ', '-', $request->name));

                $table_name = 'cstmmenu_' . strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $request->name));

                $data['table_name'] = $table_name;

                $custom_menu_create = CustomMenu::create($data);

                // $custom_menu_label_data = [];
                // foreach ($data['label_data'] as $key => $item) {
                //     $custom_menu_label_data[] = [
                //         'custom_menu_id' => $custom_menu->id,
                //         'label' => $item['label'],
                //         'name' => $item['name'],
                //         'type_input' => $item['type_input'],
                //         'show_data' => $item['show_data'],
                //     ];
                // }

                // CustomMenuLabel::create($custom_menu_label_data);

                $permission_name = preg_replace('/[^a-zA-Z0-9]/', ' ', $request->name);
                $data_permission = [
                    "{$permission_name}",
                    "{$permission_name}.Create",
                    "{$permission_name}.Edit",
                    "{$permission_name}.Delete"
                ];

                foreach ($data_permission as $permission) {
                    Permission::create(['name' => $permission]);
                }

                if (!Schema::hasTable($table_name)) {
                    $old_table_name = $custom_menu->table_name;
                    DB::statement("CREATE TABLE $table_name LIKE $old_table_name;");
                }
            });

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diduplikat!'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan, silahkan coba lagi!'
            ]);
        }
    }

    public function dataTableMenu(Request $request, $slug)
    {
        $data = CustomMenu::where('slug', $slug)->first();
        if (!$data)
            return abort(404);

        $data = $this->generatedataCustomMenu($data);

        $table_name = $data->table_name;
        $menu_value = DB::table($table_name)
            ->leftJoin('users', 'users.id', '=', "{$table_name}.created_by")
            ->leftJoin('users as updated_by', 'updated_by.id', '=', "{$table_name}.updated_by")
            ->select("{$table_name}.*", 'users.name as created_by_name', 'updated_by.name as updated_by_name');

        if(empty($request->order[0]['column'])) {
            $menu_value = $menu_value->orderBy("{$table_name}.id", 'DESC');
        }

        $filter = $request->filter ?? [];
        foreach ($filter as $key => $item) {
            if($key === 'type' || $key === 'range_date') continue;

            if ($key !== '' && $item !== '') {
                $menu_value = $menu_value->where($key, $item);
            }
        }

        $keyword = $request->keyword ?? '';
        if($keyword) {
            foreach ($data->label_show as $key => $item) {
                $menu_value = $menu_value->orWhere("$table_name.".$item['name'], 'like', '%' . $keyword . '%');
            }
        }

        $menu_value = $menu_value->when($filter['type'] ?? false, function ($query) use ($filter, $table_name) {
            $type = $filter['type'];
            switch($type) {
                case 'now':
                    return $query->whereDate("$table_name.created_at", today());
                    break;
                case 'yesterday':
                    return $query->whereDate("$table_name.created_at", today()->subDays(1));
                    break;
                case 'lastWeek':
                    return $query->whereBetween("$table_name.created_at", [today()->subWeek(), today()]);
                    break;
                case '30day':
                    $tanggalAwal = Carbon::now()->subMonth();
                    $tanggalAkhir = Carbon::now();
                    return $query->whereDate("$table_name.created_at", '>=', $tanggalAwal)
                                ->whereDate("$table_name.created_at", '<=', $tanggalAkhir);
                    break;
                case 'range':
                    $date_range = explode(' to ', $filter['range_date']);
                    return $query->whereDate("$table_name.created_at", '>=', $date_range[0])->whereDate("$table_name.created_at", '<=', $date_range[1] ?? $date_range[0]);
                    break;
            }
        });

        $datatable = DataTables::of($menu_value)
            ->addColumn('action', function ($item) use ($slug, $data) {
            $actionButton = '';
            if (canPermission("$data->permission_name.Edit"))
                $actionButton .= '<a href="' . route('custom-menu.menu.edit', [$slug, $item->id]) . '" class="dropdown-item">
                                                        <i class="fa fa-fw fa-edit"></i>
                                                        Edit
                                                    </a>';

            if (canPermission("$data->permission_name.Delete"))
                $actionButton .= '  <a href="javascript:void(0)" data-id="' . $item->id . '"
                                                        class=" js-bs-tooltip-enabled btn-delete dropdown-item"
                                                        data-bs-toggle="tooltip" title="Delete"><i
                                                            class="fa fa-fw fa-trash-alt"></i>
                                                        Hapus
                                                    </a>';
            return '
                                    <div class="d-flex align-items-center gap-2">
                                        <button
                                            class="btn btn-sm btn-alt-secondary btn-view"
                                            type="button"
                                            data-id="' . $item->id . '">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <div class="dropdown">
                                            <button
                                                class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled btn-print"
                                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                ' . $actionButton . '
                                            </ul>
                                        </div>
                                    </div>
                                    ';
        })->rawColumns(['action']);

        $datatable = $datatable->addindexColumn()
            ->smart(true)
            ->make(true);
        return $datatable;
    }

    public function showMenu($slug,$id)
    {
        $custom_menu = CustomMenu::where('slug', $slug)->first();
        if (!$custom_menu)
            return abort(404);

        $custom_menu = $this->generatedataCustomMenu($custom_menu);
        $data = DB::table($custom_menu->table_name)->where('id', $id)->first();
        if (!$data)
            return abort(404);

        $data = collect((array) $data);

        return view('custom_menu.menu.show', compact('data', 'custom_menu'));
    }

    public function menuUpdateEditable(Request $request, $slug, $id)
    {
        $validator = Validator::make($request->all(), [
            'value' => 'required',
            'name' => 'required',
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak valid!'
            ]);
        }

        $data = CustomMenu::where('slug', $slug)->first();

        if (!$data)
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan!'
            ]);

        $data = $this->generatedataCustomMenu($data);

        $table_name = $data->table_name;
        $field_name = $request->name;
        $value = $request->value;
        $id = $request->id;

        try {
            $exists = DB::table($table_name)
                ->where('id', $id)
                ->exists();

            if (!$exists)
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan!'
                ]);

            $data_update = [
                $field_name => $value
            ];

            DB::transaction(function () use ($table_name, $data_update, $id) {
                DB::table($table_name)
                    ->where('id', $id)
                    ->update($data_update);
            });

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diubah!'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan, silahkan coba lagi!'
            ]);
        }
    }

    public function menuExportExcel(Request $request, $slug)
    {

        $data = CustomMenu::where('slug', $slug)->first();
        if (!$data)
            return abort(404);
        $data = $this->generatedataCustomMenu($data);

        $table_name = $data->table_name;
        $data_value = DB::table($table_name)
            ->select("{$table_name}.*", 'users.name as created_by', 'updated_by.name as updated_by')
            ->leftJoin('users', 'users.id', '=', "{$table_name}.created_by")
            ->leftJoin('users as updated_by', 'updated_by.id', '=', "{$table_name}.updated_by")
            ->orderBy("{$table_name}.id", 'DESC')
            ->get()
            ->map(function ($item) {
                return (array) $item;
            });

        return response(view('custom_menu.menu.exportExcel', compact('data', 'data_value')))
            ->header('Content-Type', 'application/vnd-ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $data->name . ' (' . date('d F Y') . ').xls"');
    }
}
