<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomepageContentSeeder extends Seeder
{
    public function run()
    {
        // Locations we will (re)seed
        $locations = [
            'newdesign_why_choose',
            'newdesign_features',
            'newdesign_sale_banner',
            'newdesign_brands'
        ];

        // Remove existing rows for these locations
        DB::table('homepages')->whereIn('Location', $locations)->delete();

        $now = Carbon::now();

        // Why Choose Us (single hero + points + CTA)
        DB::table('homepages')->insert([
            'Location' => 'newdesign_why_choose',
            'en_Title' => '100% Trusted\nOrganic Food Store',
            'en_Description_One' => 'Healthy & natural food for lovers of healthy food.',
            'en_Description_Two' => "Healthy & natural food for lovers of healthy food.\nEvery day fresh and quality products for you.",
            'en_button_text' => 'Shop Now',
            'en_button_url' => url('/'),
            'fr_Title' => '100% Confiance\nMagasin d\'aliments biologiques',
            'fr_Description_One' => 'Aliments sains et naturels pour les amoureux de la nourriture saine.',
            'fr_Description_Two' => "Aliments sains et naturels pour les amateurs de nourriture saine.\nChaque jour des produits frais et de qualité pour vous.",
            'image' => NULL,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Features block (four items). We'll store them in a single row with dedicated columns.
        DB::table('homepages')->insert([
            'Location' => 'newdesign_features',
            'en_Title' => 'Why Choose Us',
            'en_Description_One' => 'Why choose our store — quality, freshness and trust.',
            'fr_Title' => 'Pourquoi nous choisir',
            'fr_Description_One' => 'Pourquoi choisir notre magasin - qualité, fraîcheur et confiance.',
            'en_feature_1_title' => 'Fast delivery within 20 min',
            'en_feature_1_desc' => 'Free shipping on all your order.',
            'en_feature_2_title' => 'Customer Support 24/7',
            'en_feature_2_desc' => 'Instant access to Support',
            'en_feature_3_title' => '100% Secure Payment',
            'en_feature_3_desc' => 'We ensure your money is safe',
            'en_feature_4_title' => 'Money-Back Guarantee',
            'en_feature_4_desc' => '30 Days Money-Back Guarantee',

            'fr_feature_1_title' => 'Livraison rapide en 20 min',
            'fr_feature_1_desc' => 'Livraison gratuite sur toutes vos commandes.',
            'fr_feature_2_title' => 'Support client 24/7',
            'fr_feature_2_desc' => 'Accès instantané au support',
            'fr_feature_3_title' => 'Paiement 100% sécurisé',
            'fr_feature_3_desc' => 'Nous assurons la sécurité de votre argent',
            'fr_feature_4_title' => 'Garantie de remboursement',
            'fr_feature_4_desc' => 'Garantie de remboursement de 30 jours',

            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Sale banner (content + button) - image can be set later via admin UI
        DB::table('homepages')->insert([
            'Location' => 'newdesign_sale_banner',
            'en_Title' => 'Sale of the Month',
            'en_Description_One' => 'Best deals and limited time offers. Don\'t miss out!',
            'en_button_text' => 'Shop Now',
            'en_button_url' => url('/'),
            'fr_Title' => 'Vente du mois',
            'fr_Description_One' => 'Meilleures offres et promotions limitées. Ne les manquez pas!',
            'fr_button_text' => 'Acheter maintenant',
            'fr_button_url' => url('/'),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Brands row (empty row; actual brand logos are stored in Advertise table; keep a CMS row to show/manage via admin if desired)
        DB::table('homepages')->insert([
            'Location' => 'newdesign_brands',
            'en_Title' => 'Our Brands',
            'en_Description_One' => 'Trusted brands we work with',
            'fr_Title' => 'Nos marques',
            'fr_Description_One' => 'Marques de confiance avec lesquelles nous travaillons',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
