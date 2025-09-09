// database/migrations/xxxx_xx_xx_xxxxxx_create_vehiculos_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->string('patente')->unique();
            $table->string('color');
            $table->year('anio'); // El tipo 'year' es ideal para años

            // Clave foránea que conecta con la tabla 'modelos'
            $table->foreignId('modelo_id')->constrained('modelos')->onDelete('cascade');

            $table->timestamps();
        });
    }
    // ...
};
