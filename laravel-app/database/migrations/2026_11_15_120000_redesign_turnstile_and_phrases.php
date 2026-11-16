<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const PHRASES = [
        ['hero.greeting', 'Hero', "Hello, I'm", 'Hola, soy'],
        ['hero.roles', 'Hero', 'Drilling Engineer|AI/ML Researcher|Ph.D. in Petroleum Engineering|Educator', 'Ingeniero de Perforación|Investigador en IA/ML|Ph.D. en Ingeniería de Petróleos|Educador'],
        ['footer.cta_title', 'Footer', "Let's build the future of drilling together.", 'Construyamos juntos el futuro de la perforación.'],
        ['footer.cta_text', 'Footer', 'Open to research collaborations, industry projects and speaking invitations.', 'Abierto a colaboraciones de investigación, proyectos con la industria e invitaciones como conferencista.'],
        ['section_lead.about', 'Section Headings', 'Engineer, researcher and educator bridging physics and artificial intelligence.', 'Ingeniero, investigador y educador que une la física y la inteligencia artificial.'],
        ['section_lead.news', 'Section Headings', 'Conferences, awards and milestones from the field and the lab.', 'Conferencias, premios y logros desde el campo y el laboratorio.'],
        ['section_lead.research', 'Section Headings', 'Hybrid physics + AI models for safer, smarter well construction.', 'Modelos híbridos física + IA para una construcción de pozos más segura e inteligente.'],
        ['section_lead.publications', 'Section Headings', 'Peer-reviewed journal articles and conference papers.', 'Artículos en revistas arbitradas y ponencias en congresos.'],
        ['section_lead.awards', 'Section Headings', 'Recognition for research, presentations and academic work.', 'Reconocimientos a la investigación, las presentaciones y el trabajo académico.'],
        ['section_lead.blog', 'Section Headings', 'Notes on drilling, data science and engineering.', 'Notas sobre perforación, ciencia de datos e ingeniería.'],
        ['section_lead.contact', 'Section Headings', "Let's talk about your next project.", 'Hablemos de tu próximo proyecto.'],
    ];

    public function up(): void
    {
        if (Schema::hasColumn('contact_messages', 'recaptcha_verified')) {
            Schema::table('contact_messages', function (Blueprint $table) {
                $table->renameColumn('recaptcha_verified', 'captcha_verified');
            });
        }

        // reCAPTCHA keys are not valid for Turnstile; they must be re-entered.
        DB::table('settings')->whereIn('key', ['recaptcha_site_key', 'recaptcha_secret_key'])->delete();

        $now = now();

        foreach (self::PHRASES as [$key, $group, $en, $es]) {
            if (DB::table('public_phrases')->where('key', $key)->exists()) {
                continue;
            }

            DB::table('public_phrases')->insert([
                'key' => $key,
                'group' => $group,
                'value' => json_encode(['en' => $en, 'es' => $es], JSON_UNESCAPED_UNICODE),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('contact_messages', 'captcha_verified')) {
            Schema::table('contact_messages', function (Blueprint $table) {
                $table->renameColumn('captcha_verified', 'recaptcha_verified');
            });
        }

        DB::table('public_phrases')->whereIn('key', array_column(self::PHRASES, 0))->delete();
    }
};
