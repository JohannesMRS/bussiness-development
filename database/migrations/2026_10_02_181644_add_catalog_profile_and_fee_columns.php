<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'whatsapp_number')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->string('whatsapp_number', 20)->nullable();
            });
        }

        if (! Schema::hasColumn('users', 'major')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->string('major', 100)->nullable();
            });
        }

        if (! Schema::hasColumn('users', 'business_name')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->string('business_name')->nullable();
            });
        }

        if (! Schema::hasColumn('users', 'business_description')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->text('business_description')->nullable();
            });
        }

        if (! Schema::hasColumn('users', 'avatar_url')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->string('avatar_url')->nullable();
            });
        }

        if (Schema::hasColumn('users', 'phone')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->string('phone', 50)->nullable()->change();
            });
        }

        if (! Schema::hasColumn('users', 'bussiness_name')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->string('bussiness_name')->nullable();
            });
        }

        if (! Schema::hasColumn('users', 'bussiness_description')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->text('bussiness_description')->nullable();
            });
        }

        if (Schema::hasColumn('users', 'business_name')) {
            DB::table('users')
                ->whereNull('bussiness_name')
                ->whereNotNull('business_name')
                ->update(['bussiness_name' => DB::raw('business_name')]);
        }

        if (Schema::hasColumn('users', 'business_description')) {
            DB::table('users')
                ->whereNull('bussiness_description')
                ->whereNotNull('business_description')
                ->update(['bussiness_description' => DB::raw('business_description')]);
        }

        if (! Schema::hasColumn('users', 'is_active')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->boolean('is_active')->default(true);
            });
        }

        if (! Schema::hasColumn('products', 'fee_amount')) {
            Schema::table('products', function (Blueprint $table): void {
                $table->unsignedBigInteger('fee_amount')->default(0);
            });

            DB::table('products')
                ->join('users', 'users.id', '=', 'products.user_id')
                ->where('users.role', 'seller')
                ->select('products.id', 'products.price')
                ->orderBy('products.id')
                ->chunkById(500, function ($products): void {
                    foreach ($products as $product) {
                        $price = (int) $product->price;
                        $feeAmount = intdiv($price, 100) + (($price % 100) >= 50 ? 1 : 0);

                        DB::table('products')
                            ->where('id', $product->id)
                            ->update(['fee_amount' => $feeAmount]);
                    }
                }, 'products.id', 'id');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This schema/data compatibility migration is intentionally forward-only to preserve existing account and product data.
    }
};
