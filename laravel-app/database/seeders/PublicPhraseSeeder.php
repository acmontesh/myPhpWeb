<?php

namespace Database\Seeders;

use App\Models\PublicPhrase;
use Illuminate\Database\Seeder;

class PublicPhraseSeeder extends Seeder
{
    public function run(): void
    {
        $phrases = [
            'empty_state.blog.title' => ['group' => 'Empty States', 'en' => 'No articles yet'],
            'empty_state.blog.message' => ['group' => 'Empty States', 'en' => 'New writing will appear here soon — check back later.'],
            'empty_state.research.title' => ['group' => 'Empty States', 'en' => 'No research projects yet'],
            'empty_state.research.message' => ['group' => 'Empty States', 'en' => 'Project write-ups will appear here soon.'],
            'empty_state.publications.title' => ['group' => 'Empty States', 'en' => 'No publications yet'],
            'empty_state.publications.message' => ['group' => 'Empty States', 'en' => 'Papers and conference proceedings will appear here soon.'],
            'empty_state.news.title' => ['group' => 'Empty States', 'en' => 'No news yet'],
            'empty_state.news.message' => ['group' => 'Empty States', 'en' => 'Announcements and updates will appear here soon.'],
            'empty_state.quotes.title' => ['group' => 'Empty States', 'en' => 'No quotes yet'],
            'empty_state.quotes.message' => ['group' => 'Empty States', 'en' => 'Quotes worth sharing will appear here soon.'],
            'empty_state.awards.title' => ['group' => 'Empty States', 'en' => 'No awards yet'],
            'empty_state.awards.message' => ['group' => 'Empty States', 'en' => 'Awards and distinctions will appear here soon.'],
            'empty_state.about.title' => ['group' => 'Empty States', 'en' => 'About Me is coming soon'],
            'empty_state.about.message' => ['group' => 'Empty States', 'en' => 'A full profile will appear here soon.'],

            'quotes.title' => ['group' => 'Section Headings', 'en' => 'Quotes I Like'],
            'contact.intro' => ['group' => 'Contact', 'en' => 'Have a question, a collaboration in mind, or just want to say hello? Send a message and I will get back to you.'],
        ];

        foreach ($phrases as $key => $data) {
            $phrase = PublicPhrase::query()->firstOrNew(['key' => $key]);

            if ($phrase->exists) {
                continue;
            }

            $phrase->group = $data['group'];
            $phrase->setTranslations('value', ['en' => $data['en']]);
            $phrase->save();
        }
    }
}
