<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGdprFieldsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'gdpr_consent')) {
                $table->boolean('gdpr_consent')->default(false)->after('remember_token');
            }
            if (! Schema::hasColumn('users', 'gdpr_consent_at')) {
                $table->timestamp('gdpr_consent_at')->nullable()->after('gdpr_consent');
            }
            if (! Schema::hasColumn('users', 'privacy_policy_version')) {
                $table->string('privacy_policy_version')->nullable()->after('gdpr_consent_at');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'privacy_policy_version')) {
                $table->dropColumn('privacy_policy_version');
            }
            if (Schema::hasColumn('users', 'gdpr_consent_at')) {
                $table->dropColumn('gdpr_consent_at');
            }
            if (Schema::hasColumn('users', 'gdpr_consent')) {
                $table->dropColumn('gdpr_consent');
            }
        });
    }
}
