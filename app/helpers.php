<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

if (!function_exists('formatDate')) {
    function formatDate($tanggal)
    {
        $bulan = array(
            1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        $explode = explode(' ', $tanggal);

        $expDate = explode('-', $explode[0]);
        $expTime = explode(':', $explode[1]);

        return $expDate[2] . ' ' . $bulan[(int)$expDate[1]] . ' ' . $expDate[0] . ' ' .  $expTime[0] . ':' . $expTime[1] . ' WIB';
    }
}

if (!function_exists('canPermission')) {
    function canPermission($permission, $non_user = false)
    {
        $user = Auth::user();
        if ($user->role == 'admin' || ($user->role == 'user' && !$non_user)) {
            return true;
        } else {
            if ($user->can($permission)) {
                return true;
            } else {
                return false;
            }
        }
    }
}

function renameTable()
{
    if (Schema::hasTable('old_table_name')) {
        // Mengecek apakah tabel baru sudah ada
        if (!Schema::hasTable('new_table_name')) {
            DB::statement('RENAME TABLE old_table_name TO new_table_name');
            return 'Table renamed successfully!';
        } else {
            return 'New table name already exists!';
        }
    } else {
        return 'Old table does not exist!';
    }
}

function renameColumn()
{
    if (Schema::hasTable('new_table_name')) {
        if (Schema::hasColumn('new_table_name', 'old_column_name')) {
            Schema::table('new_table_name', function (Blueprint $table) {
                $table->renameColumn('old_column_name', 'new_column_name');
            });
            return 'Column renamed successfully!';
        } else {
            return 'Old column does not exist!';
        }
    } else {
        return 'Table does not exist!';
    }
}

function addColumn()
{
    if (Schema::hasTable('new_table_name')) {
        Schema::table('new_table_name', function (Blueprint $table) {
            $table->string('new_column')->nullable();
        });
        return 'Column added successfully!';
    } else {
        return 'Table does not exist!';
    }
}

function dropColumn()
{
    if (Schema::hasTable('new_table_name')) {
        if (Schema::hasColumn('new_table_name', 'column_to_remove')) {
            Schema::table('new_table_name', function (Blueprint $table) {
                $table->dropColumn('column_to_remove');
            });
            return 'Column dropped successfully!';
        } else {
            return 'Column does not exist!';
        }
    } else {
        return 'Table does not exist!';
    }
}

function upload_image($file, $path, $name){
    $filename = $name . time(). rand(1,9999) .'.' . $file->getClientOriginalExtension();
    $destinationPath = 'uploads/images/' . $path;

    // Memeriksa apakah direktori sudah ada, jika tidak, buat direktori
    if (!File::isDirectory($destinationPath)) {
        File::makeDirectory($destinationPath, 0755, true, true);
    }

    // Pindahkan file ke direktori tujuan
    $file->move($destinationPath, $filename);

    return $destinationPath . '/' . $filename;
}