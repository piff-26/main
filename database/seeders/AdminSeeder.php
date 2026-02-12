<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\Division;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $divisions = Division::all()->keyBy('slug');

        $adminsData = [

            // Steering Committee
            ['name' => 'Marvel Fanuel', 'nrp' => 'C14220113', 'email' => 'c14220113@john.petra.ac.id', 'division_slug' => 'sc'],
            ['name' => 'Valencia Octaviany', 'nrp' => 'C14220287', 'email' => 'c14220287@john.petra.ac.id', 'division_slug' => 'sc'],
            ['name' => 'Frandio Chrisna Hendra Winata', 'nrp' => 'C13230049', 'email' => 'c13230049@john.petra.ac.id', 'division_slug' => 'sc'],
            ['name' => 'Laura Nathania Celoviantono', 'nrp' => 'D11230162', 'email' => 'd11230162@john.petra.ac.id', 'division_slug' => 'sc'],
            ['name' => 'Hendrawan Surya Wijaya', 'nrp' => 'H15240031', 'email' => 'h15240031@john.petra.ac.id', 'division_slug' => 'sc'],

            // Badan Pengurus Harian
            ['name' => 'Michael Juan Ivander Widiarto', 'nrp' => 'H15240015', 'email' => 'h15240015@john.petra.ac.id', 'division_slug' => 'bph'],
            ['name' => 'Felix Augurius Leneka', 'nrp' => 'C14240053', 'email' => 'c14240053@john.petra.ac.id', 'division_slug' => 'bph'],
            ['name' => 'Chaterine Cristela Sudhana', 'nrp' => 'H13240044', 'email' => 'h13240044@john.petra.ac.id', 'division_slug' => 'bph'],

            // Divisi Acara
            ['name' => 'Aileen Clarissa Prasetia', 'nrp' => 'H15240021', 'email' => 'h15240021@john.petra.ac.id', 'division_slug' => 'acara'],
            ['name' => 'Graciela Priscilia Widodo', 'nrp' => 'H15240022', 'email' => 'h15240022@john.petra.ac.id', 'division_slug' => 'acara'],
            ['name' => 'Rachel Nathania', 'nrp' => 'D12240034', 'email' => 'd12240034@john.petra.ac.id', 'division_slug' => 'acara'],
            ['name' => 'Evelyn Gabriella Widyanto', 'nrp' => 'H15250012', 'email' => 'h15250012@john.petra.ac.id', 'division_slug' => 'acara'],
            ['name' => 'Simone Hanumi', 'nrp' => 'H15250015', 'email' => 'h15250015@john.petra.ac.id', 'division_slug' => 'acara'],
            ['name' => 'Patricio Kenneth Liemsela', 'nrp' => 'H15250007', 'email' => 'h15250007@john.petra.ac.id', 'division_slug' => 'acara'],
            ['name' => 'Reinard Vincent Budiman', 'nrp' => 'H15250042', 'email' => 'h15250042@john.petra.ac.id', 'division_slug' => 'acara'],
            ['name' => 'Gweneth Alandra Kahar', 'nrp' => 'D12240064', 'email' => 'd12240064@john.petra.ac.id', 'division_slug' => 'acara'],

            // Transportasi, Perlengkapan dan Keamanan
            ['name' => 'Wayne Aldrich', 'nrp' => 'C14240011', 'email' => 'c14240011@john.petra.ac.id', 'division_slug' => 'transkapman'],
            ['name' => 'Nelson Susanto', 'nrp' => 'C14240041', 'email' => 'c14240041@john.petra.ac.id', 'division_slug' => 'transkapman'],
            ['name' => 'Juliaan Matthew Wongsodjaja', 'nrp' => 'C14240017', 'email' => 'c14240017@john.petra.ac.id', 'division_slug' => 'transkapman'],
            ['name' => 'Darian Joseph Setiabudi', 'nrp' => 'B11240014', 'email' => 'b11240014@john.petra.ac.id', 'division_slug' => 'transkapman'],
            ['name' => 'Vinsens Sandriawan', 'nrp' => 'C14240012', 'email' => 'c14240012@john.petra.ac.id', 'division_slug' => 'transkapman'],
            ['name' => 'Shane Archie', 'nrp' => 'D11250286', 'email' => 'd11250286@john.petra.ac.id', 'division_slug' => 'transkapman'],
            ['name' => 'Christopher Aaron Sugiarto', 'nrp' => 'C14240137', 'email' => 'c14240137@john.petra.ac.id', 'division_slug' => 'transkapman'],
            ['name' => 'Gishella Agnessita Damopolii', 'nrp' => 'D11250274', 'email' => 'd11250274@john.petra.ac.id', 'division_slug' => 'transkapman'],
            ['name' => 'Dave Marcellino Aliyan', 'nrp' => 'C14240122', 'email' => 'c14240122@john.petra.ac.id', 'division_slug' => 'transkapman'],
            ['name' => 'Ezra Desmond Sutanto', 'nrp' => 'C14240176', 'email' => 'c14240176@john.petra.ac.id', 'division_slug' => 'transkapman'],
            ['name' => 'Maximillian Davidson', 'nrp' => 'C14240116', 'email' => 'c14240116@john.petra.ac.id', 'division_slug' => 'transkapman'],
            ['name' => 'Sean Vandana', 'nrp' => 'C14240092', 'email' => 'c14240092@john.petra.ac.id', 'division_slug' => 'transkapman'],
            ['name' => 'Christofer Jason Heriyono', 'nrp' => 'C14240024', 'email' => 'c14240024@john.petra.ac.id', 'division_slug' => 'transkapman'],
            ['name' => 'Aaron Juan Kurnia', 'nrp' => 'D11240100', 'email' => 'd11240100@john.petra.ac.id', 'division_slug' => 'transkapman'],
            // ['name' => 'Gabriel', 'nrp' => null, 'email' => null, 'division_slug' => 'transkapman'], //Datanya blm lengkap

            // Divisi Creative
            ['name' => 'Thyodore Elisafan Sakul', 'nrp' => 'H14240086', 'email' => 'h14240086@john.petra.ac.id', 'division_slug' => 'creative'],
            ['name' => 'Natasya Amelia Halim', 'nrp' => 'H14240002', 'email' => 'h14240002@john.petra.ac.id', 'division_slug' => 'creative'],
            ['name' => 'Bryan Emerson Ruslim', 'nrp' => 'H14240006', 'email' => 'h14240006@john.petra.ac.id', 'division_slug' => 'creative'],
            ['name' => 'Alisa Joice Harsono', 'nrp' => 'H14240152', 'email' => 'h14240152@john.petra.ac.id', 'division_slug' => 'creative'],
            ['name' => 'Valerie Grace Widiyanto', 'nrp' => 'H14240118', 'email' => 'h14240118@john.petra.ac.id', 'division_slug' => 'creative'],
            ['name' => 'Maria Irene Fidelia', 'nrp' => 'H14240060', 'email' => 'h14240060@john.petra.ac.id', 'division_slug' => 'creative'],
            ['name' => 'Gracia Wanda Tjahyani', 'nrp' => 'H14240085', 'email' => 'h14240085@john.petra.ac.id', 'division_slug' => 'creative'],
            ['name' => 'Dylan Christian Lumingas', 'nrp' => 'H14240175', 'email' => 'h14240175@john.petra.ac.id', 'division_slug' => 'creative'],
            ['name' => 'Winston Wei', 'nrp' => 'H14240083', 'email' => 'h14240083@john.petra.ac.id', 'division_slug' => 'creative'],
            ['name' => 'Clarabelle Charlotte Tomatala', 'nrp' => 'H14250142', 'email' => 'h14250142@john.petra.ac.id', 'division_slug' => 'creative'],

            // Sekretariat dan Konsumsi
            ['name' => 'Clarissa Faustina Widjaja', 'nrp' => 'H15240004', 'email' => 'h15240004@john.petra.ac.id', 'division_slug' => 'sekkon'],
            ['name' => 'Jessica Novellina', 'nrp' => 'D12240055', 'email' => 'd12240055@john.petra.ac.id', 'division_slug' => 'sekkon'],
            ['name' => 'Estee Excelyn', 'nrp' => 'D11240002', 'email' => 'd11240002@john.petra.ac.id', 'division_slug' => 'sekkon'],
            ['name' => 'Ruth Eliza', 'nrp' => 'D11240108', 'email' => 'd11240108@john.petra.ac.id', 'division_slug' => 'sekkon'],
            ['name' => 'Kezya Gracella', 'nrp' => 'D12240094', 'email' => 'd12240094@john.petra.ac.id', 'division_slug' => 'sekkon'],
            ['name' => 'Nathania Tabemono', 'nrp' => 'C14240027', 'email' => 'c14240027@john.petra.ac.id', 'division_slug' => 'sekkon'],
            ['name' => 'Felisia Putri Santoso', 'nrp' => 'H15250050', 'email' => 'h15250050@john.petra.ac.id', 'division_slug' => 'sekkon'],

            // Divisi IT
            ['name' => 'Nataniel Joshe', 'nrp' => 'C14240154', 'email' => 'c14240154@john.petra.ac.id', 'division_slug' => 'it'],
            ['name' => 'Felix Valerian Sutanto', 'nrp' => 'C14240190', 'email' => 'c14240190@john.petra.ac.id', 'division_slug' => 'it'],
            ['name' => 'Jonathan Christian', 'nrp' => 'C14240075', 'email' => 'c14240075@john.petra.ac.id', 'division_slug' => 'it'],

            // Sponsorship dan Partnership
            ['name' => 'Tirsa Kristia Aurelia', 'nrp' => 'H15240040', 'email' => 'h15240040@john.petra.ac.id', 'division_slug' => 'sponsor'],
            ['name' => 'Livina Renata', 'nrp' => 'H15240003', 'email' => 'h15240003@john.petra.ac.id', 'division_slug' => 'sponsor'],
            ['name' => 'Ida Bagus Made Kesawa Telaga', 'nrp' => 'H15240055', 'email' => 'h15240055@john.petra.ac.id', 'division_slug' => 'sponsor'],
            ['name' => 'Andre Manuel Tanoto', 'nrp' => 'H15240009', 'email' => 'h15240009@john.petra.ac.id', 'division_slug' => 'sponsor'],
            ['name' => 'Jeanny Ariyanto', 'nrp' => 'D11250014', 'email' => 'd11250014@john.petra.ac.id', 'division_slug' => 'sponsor'],
            ['name' => 'Jesselyn Louisa', 'nrp' => 'H14240012', 'email' => 'h14240012@john.petra.ac.id', 'division_slug' => 'sponsor'],
        ];

        foreach ($adminsData as $adminData) {

            if (!isset($divisions[$adminData['division_slug']])) {
                throw new \Exception("Division slug '{$adminData['division_slug']}' not found.");
            }

            Admin::create([
                'id' => Str::uuid(),
                'name' => $adminData['name'],
                'nrp' => $adminData['nrp'],
                'email' => $adminData['email'],
                'division_id' => $divisions[$adminData['division_slug']]->id,
            ]);
        }
    }
}
