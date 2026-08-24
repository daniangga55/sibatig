<?php

namespace Tests\Feature;

use App\Enums\DocumentCategory;
use App\Enums\PkptStatus;
use App\Enums\UserRole;
use App\Filament\Pages\KalenderKegiatan;
use App\Filament\Pages\Pengumuman;
use App\Filament\Resources\Documents\Pages\CreateDocument;
use App\Filament\Resources\Documents\Pages\EditDocument;
use App\Filament\Resources\Documents\Pages\ListDocuments;
use App\Filament\Resources\Documents\Pages\ViewDocument;
use App\Filament\Resources\MonitoringEvaluations\MonitoringEvaluationResource;
use App\Filament\Resources\MonitoringEvaluations\Pages\CreateMonitoringEvaluation;
use App\Filament\Resources\MonitoringEvaluations\Pages\EditMonitoringEvaluation;
use App\Filament\Resources\MonitoringEvaluations\Pages\ViewMonitoringEvaluation;
use App\Filament\Resources\PkptActivities\Pages\CreatePkptActivity;
use App\Filament\Resources\PkptActivities\Pages\EditPkptActivity;
use App\Filament\Resources\PkptActivities\Pages\ListPkptActivities;
use App\Filament\Resources\PkptActivities\Pages\ViewPkptActivity;
use App\Filament\Resources\SptRecords\Pages\CreateSptRecord;
use App\Filament\Resources\SptRecords\Pages\EditSptRecord;
use App\Filament\Resources\SptRecords\Pages\ListSptRecords;
use App\Filament\Resources\SptRecords\Pages\ViewSptRecord;
use App\Filament\Resources\TeamMembers\Pages\CreateTeamMember;
use App\Filament\Resources\TeamMembers\Pages\EditTeamMember;
use App\Filament\Resources\TeamMembers\Pages\ListTeamMembers;
use App\Filament\Resources\TeamMembers\Pages\ViewTeamMember;
use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Pages\ViewUser;
use App\Filament\Resources\WebsiteSettings\Pages\EditWebsiteSetting;
use App\Filament\Resources\WebsiteSettings\Pages\ListWebsiteSettings;
use App\Filament\Resources\WebsiteSettings\Pages\ViewWebsiteSetting;
use App\Filament\Widgets\PkptStatsOverview;
use App\Filament\Widgets\PkptStatusChart;
use App\Filament\Widgets\RecentMonitoring;
use App\Models\Document;
use App\Models\MonitoringEvaluation;
use App\Models\PkptActivity;
use App\Models\SptRecord;
use App\Models\TeamMember;
use App\Models\User;
use App\Models\WebsiteSetting;
use App\Support\SibatigMetrics;
use App\Support\SptDocumentSync;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\MonitoringEvaluationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class SibatigPanelTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
        $this->admin = User::query()->where('email', 'admin@sibatig.local')->firstOrFail();
        $this->actingAs($this->admin);
    }

    public function test_seeded_data_and_primary_pages_are_available(): void
    {
        $this->assertDatabaseCount('team_members', 7);
        $this->assertDatabaseCount('pkpt_activities', 29);
        $this->assertDatabaseCount('monitoring_evaluations', 11);
        $this->assertDatabaseCount('spt_records', 30);

        Livewire::test(ListUsers::class)->assertSuccessful();
        Livewire::test(ListTeamMembers::class)->assertSuccessful();
        Livewire::test(ListPkptActivities::class)->assertSuccessful();
        Livewire::test(ListWebsiteSettings::class)->assertSuccessful();
    }

    public function test_spt_and_operational_navigation_pages_are_available(): void
    {
        $record = SptRecord::query()->firstOrFail();

        Livewire::test(ListSptRecords::class)->assertSuccessful();
        Livewire::test(CreateSptRecord::class)
            ->assertSuccessful()
            ->assertSet('activeSptTab', 1)
            ->assertSet('highestAccessibleSptTab', 1)
            ->assertSee('Identitas SPT')
            ->assertSee('Integrasi PKPT &amp; Laporan', false)
            ->assertSee('File SPT')
            ->assertSee('Batal');
        Livewire::test(ViewSptRecord::class, ['record' => $record->getRouteKey()])
            ->assertSuccessful()
            ->assertSee('Lihat di Kalender');
        Livewire::test(EditSptRecord::class, ['record' => $record->getRouteKey()])->assertSuccessful()->assertSee('Batal');
        Livewire::test(KalenderKegiatan::class)
            ->set('year', 2026)
            ->set('month', 8)
            ->assertSuccessful()
            ->assertSee('sibatig-calendar-grid', false)
            ->assertSee('Agustus 2026')
            ->assertSee('Proklamasi Kemerdekaan')
            ->assertSee('Maulid Nabi Muhammad saw.')
            ->call('previousMonth')
            ->assertSet('month', 7)
            ->assertSee('Juli 2026')
            ->call('selectDate', '2026-07-30')
            ->assertSet('selectedDate', '2026-07-30')
            ->assertSet('agendaVisible', true)
            ->assertSee('700.1.2/906/419.060/2026')
            ->call('selectDate', '2026-07-30')
            ->assertSet('agendaVisible', false)
            ->assertDontSee('700.1.2/906/419.060/2026');
        Livewire::withQueryParams(['tanggal' => '2026-07-30'])
            ->test(KalenderKegiatan::class)
            ->assertSet('year', 2026)
            ->assertSet('month', 7)
            ->assertSet('selectedDate', '2026-07-30')
            ->assertSet('agendaVisible', true)
            ->assertSee('700.1.2/906/419.060/2026');
        Livewire::withQueryParams(['tanggal' => '2026-99-99'])
            ->test(KalenderKegiatan::class)
            ->assertSuccessful()
            ->assertSet('agendaVisible', false);
        Livewire::test(ListDocuments::class)->assertSuccessful();
        Livewire::test(Pengumuman::class)->assertSuccessful();

        $this->get('/admin')
            ->assertOk()
            ->assertSee('Rekap SPT')
            ->assertSee('Kalender Kegiatan')
            ->assertSee('Dokumen')
            ->assertSee('Pengumuman')
            ->assertSee('Inspektorat Kota Kediri');
    }

    public function test_spt_form_tabs_must_be_completed_in_sequence(): void
    {
        Livewire::test(CreateSptRecord::class)
            ->call('advanceSptTab', 1)
            ->assertSet('activeSptTab', 1)
            ->assertSet('highestAccessibleSptTab', 1)
            ->set('data.year', 2026)
            ->set('data.source_number', 31)
            ->set('data.document_number', '700.1.2/999/419.060/2026')
            ->set('data.document_date', '2026-08-14')
            ->set('data.start_date', '2026-08-14')
            ->set('data.subject', 'Pengujian alur tab SPT')
            ->call('advanceSptTab', 1)
            ->assertSet('activeSptTab', 2)
            ->assertSet('highestAccessibleSptTab', 2)
            ->call('advanceSptTab', 2)
            ->assertSet('activeSptTab', 2)
            ->set('data.relation_type', 'NON PKPT')
            ->set('data.assignment_type', 'REVIU')
            ->set('data.status', 'ON PROGRES')
            ->call('advanceSptTab', 2)
            ->assertSet('activeSptTab', 3)
            ->assertSet('highestAccessibleSptTab', 3)
            ->call('returnToSptTab', 2)
            ->assertSet('activeSptTab', 2)
            ->call('returnToSptTab', 1)
            ->assertSet('activeSptTab', 1);
    }

    public function test_monitoring_badge_counts_unique_pkpt_activities_and_seeder_is_idempotent(): void
    {
        $this->assertSame('11', MonitoringEvaluationResource::getNavigationBadge());

        $this->seed(MonitoringEvaluationSeeder::class);

        $this->assertDatabaseCount('monitoring_evaluations', 11);
        $this->assertSame('11', MonitoringEvaluationResource::getNavigationBadge());
    }

    public function test_cached_navigation_metrics_are_invalidated_after_data_changes(): void
    {
        SibatigMetrics::forget();
        $this->assertSame(7, SibatigMetrics::get('team_active'));

        $member = TeamMember::query()->firstOrFail();
        $member->update(['is_active' => false]);
        $this->assertSame(6, SibatigMetrics::get('team_active'));

        $member->update(['is_active' => true]);
        $this->assertSame(7, SibatigMetrics::get('team_active'));
    }

    public function test_authentication_pages_and_dashboard_widgets_render(): void
    {
        auth()->logout();
        $this->get('/admin/login')
            ->assertOk()
            ->assertSee('sibatig-auth-showcase', false)
            ->assertSee('images/logo-irban-3.jpg?v=20260819', false)
            ->assertDontSee('M11 12.5h7v18', false);
        $this->get('/admin/password-reset/request')->assertOk();

        $this->actingAs($this->admin)
            ->get('/admin')
            ->assertOk()
            ->assertSee('Kinerja pengawasan')
            ->assertSee('Selamat datang kembali')
            ->assertSee('sibatig-topbar-profile-copy', false)
            ->assertSee('images/logo-irban-3.jpg?v=20260819', false)
            ->assertSee('sibatig-stats-grid', false)
            ->assertSee('INTEGRASI PKPT&ndash;MONITORING', false)
            ->assertSee('--sibatig-primary: #1769d2', false);
        Livewire::test(PkptStatsOverview::class)->assertSuccessful();
        Livewire::test(PkptStatusChart::class)->assertSuccessful();
        Livewire::test(RecentMonitoring::class)->assertSuccessful();
    }

    public function test_full_page_forms_render_with_reliable_cancel_action(): void
    {
        Livewire::test(CreateUser::class)->assertSuccessful()->assertSee('Batal');
        Livewire::test(CreateTeamMember::class)->assertSuccessful()->assertSee('Batal');
        Livewire::test(CreatePkptActivity::class)->assertSuccessful()->assertSee('Batal');
        Livewire::test(CreateMonitoringEvaluation::class)->assertSuccessful()->assertSee('Batal');
        Livewire::test(CreateDocument::class)->assertSuccessful()->assertSee('Batal');
    }

    public function test_view_and_edit_pages_render_for_every_primary_resource(): void
    {
        $member = TeamMember::query()->firstOrFail();
        $activity = PkptActivity::query()->firstOrFail();
        $evaluation = MonitoringEvaluation::query()->firstOrFail();
        $setting = WebsiteSetting::query()->firstOrFail();

        Livewire::test(ViewUser::class, ['record' => $this->admin->getRouteKey()])->assertSuccessful();
        Livewire::test(EditUser::class, ['record' => $this->admin->getRouteKey()])->assertSuccessful()->assertSee('Batal');
        Livewire::test(ViewTeamMember::class, ['record' => $member->getRouteKey()])->assertSuccessful();
        Livewire::test(EditTeamMember::class, ['record' => $member->getRouteKey()])->assertSuccessful()->assertSee('Batal');
        Livewire::test(ViewPkptActivity::class, ['record' => $activity->getRouteKey()])->assertSuccessful();
        Livewire::test(EditPkptActivity::class, ['record' => $activity->getRouteKey()])->assertSuccessful()->assertSee('Batal');
        Livewire::test(ViewMonitoringEvaluation::class, ['record' => $evaluation->getRouteKey()])->assertSuccessful();
        Livewire::test(EditMonitoringEvaluation::class, ['record' => $evaluation->getRouteKey()])->assertSuccessful()->assertSee('Batal');
        Livewire::test(ViewWebsiteSetting::class, ['record' => $setting->getRouteKey()])->assertSuccessful();
        Livewire::test(EditWebsiteSetting::class, ['record' => $setting->getRouteKey()])->assertSuccessful()->assertSee('Batal');
    }

    public function test_website_theme_colors_can_be_changed_and_are_rendered(): void
    {
        $setting = WebsiteSetting::query()->firstOrFail();

        Livewire::test(EditWebsiteSetting::class, ['record' => $setting->getRouteKey()])
            ->fillForm([
                'theme_preset' => 'violet',
                'primary_color' => '#6d4aff',
                'accent_color' => '#ec4899',
                'sidebar_color' => '#25124a',
                'canvas_color' => '#f7f5fc',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->get('/admin')
            ->assertOk()
            ->assertSee('--sibatig-primary: #6d4aff', false)
            ->assertSee('--sibatig-sidebar: #25124a', false);
    }

    public function test_team_member_crud_create_flow_works(): void
    {
        Livewire::test(CreateTeamMember::class)
            ->fillForm([
                'full_name' => 'Anggota Pengujian, S.E.',
                'nip' => '199001012020011001',
                'position' => 'Auditor Ahli Pertama',
                'rank' => 'Penata Muda',
                'grade' => 'III/a',
                'sort_order' => 8,
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('team_members', ['nip' => '199001012020011001']);
    }

    public function test_pkpt_create_update_and_delete_flow_works(): void
    {
        $member = TeamMember::query()->firstOrFail();

        Livewire::test(CreatePkptActivity::class)
            ->fillForm([
                'year' => 2026,
                'source_number' => 60,
                'category' => 'audit',
                'assignment_type' => 'Audit Kinerja',
                'assignment' => 'Kegiatan pengujian CRUD PKPT',
                'audit_object' => 'Perangkat Daerah Pengujian',
                'executor' => 'IRBAN III',
                'apip_count' => 7,
                'status' => PkptStatus::BelumDilaksanakan->value,
                'progress' => 0,
                'teamMembers' => [$member->id],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $activity = PkptActivity::query()->where('source_number', 60)->firstOrFail();
        $this->assertTrue($activity->teamMembers()->whereKey($member->id)->exists());

        Livewire::test(EditPkptActivity::class, ['record' => $activity->getRouteKey()])
            ->fillForm(['assignment' => 'Kegiatan pengujian CRUD PKPT diperbarui'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('pkpt_activities', [
            'id' => $activity->id,
            'assignment' => 'Kegiatan pengujian CRUD PKPT diperbarui',
        ]);

        Livewire::test(EditPkptActivity::class, ['record' => $activity->getRouteKey()])
            ->callAction('delete');

        $this->assertSoftDeleted('pkpt_activities', ['id' => $activity->id]);
    }

    public function test_monitoring_update_synchronizes_pkpt_summary(): void
    {
        $activity = PkptActivity::query()->where('source_number', 4)->firstOrFail();

        Livewire::test(CreateMonitoringEvaluation::class)
            ->fillForm([
                'pkpt_activity_id' => $activity->id,
                'evaluation_date' => '2026-08-11',
                'status' => PkptStatus::Berjalan->value,
                'progress' => 60,
                'stage' => 'Pelaksanaan lapangan',
                'actual_start' => '2026-08-01',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $activity->refresh();
        $this->assertSame(PkptStatus::Berjalan, $activity->status);
        $this->assertSame(60, $activity->progress);
    }

    public function test_document_storage_crud_and_private_download_work(): void
    {
        Storage::fake('local');
        $spt = SptRecord::query()->firstOrFail();
        $file = UploadedFile::fake()->create('SPT-Pengujian.pdf', 100, 'application/pdf');

        Livewire::test(CreateDocument::class)
            ->fillForm([
                'year' => 2026,
                'category' => DocumentCategory::Spt->value,
                'title' => 'Surat Perintah Tugas Pengujian',
                'document_number' => 'TEST/SPT/2026',
                'document_date' => '2026-08-14',
                'file_path' => $file,
                'spt_record_id' => $spt->id,
                'description' => 'Dokumen pengujian penyimpanan privat.',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $document = Document::query()->where('document_number', 'TEST/SPT/2026')->firstOrFail();

        $this->assertSame($this->admin->id, $document->uploaded_by);
        $this->assertSame(DocumentCategory::Spt, $document->category);
        $this->assertSame('local', $document->storage_disk);
        $this->assertSame('SPT-Pengujian.pdf', $document->original_name);
        $this->assertSame($spt->id, $document->spt_record_id);
        Storage::disk('local')->assertExists($document->file_path);

        $this->get(route('documents.download', $document))
            ->assertOk()
            ->assertDownload('SPT-Pengujian.pdf');

        auth()->logout();
        $this->get(route('documents.download', $document))->assertRedirect('/admin/login');
        $this->actingAs($this->admin);

        Livewire::test(ViewDocument::class, ['record' => $document->getRouteKey()])
            ->assertSuccessful()
            ->assertSee('Unduh File');

        Livewire::test(EditDocument::class, ['record' => $document->getRouteKey()])
            ->fillForm(['title' => 'Surat Perintah Tugas Pengujian Diperbarui'])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertSee('Batal');

        $this->assertDatabaseHas('documents', [
            'id' => $document->id,
            'title' => 'Surat Perintah Tugas Pengujian Diperbarui',
        ]);

        Livewire::test(EditDocument::class, ['record' => $document->getRouteKey()])
            ->callAction('delete');

        $this->assertSoftDeleted('documents', ['id' => $document->id]);
        Storage::disk('local')->assertExists($document->file_path);

        $document->refresh()->forceDelete();
        Storage::disk('local')->assertMissing($document->file_path);
    }

    public function test_spt_upload_automatically_syncs_to_document_storage(): void
    {
        Storage::fake('local');
        $spt = SptRecord::query()->firstOrFail();
        $firstFile = UploadedFile::fake()->create('SPT-Utama.pdf', 120, 'application/pdf');

        Livewire::test(EditSptRecord::class, ['record' => $spt->getRouteKey()])
            ->fillForm(['spt_file' => $firstFile])
            ->call('save')
            ->assertHasNoFormErrors();

        $document = Document::query()
            ->where('spt_record_id', $spt->id)
            ->where('source', SptDocumentSync::SOURCE)
            ->firstOrFail();

        $this->assertSame(DocumentCategory::Spt, $document->category);
        $this->assertSame('local', $document->storage_disk);
        $this->assertSame($spt->document_number, $document->document_number);
        $this->assertSame('SPT-Utama.pdf', $document->original_name);
        $this->assertSame($this->admin->id, $document->uploaded_by);
        Storage::disk('local')->assertExists($document->file_path);

        Livewire::test(ViewSptRecord::class, ['record' => $spt->getRouteKey()])
            ->assertSuccessful()
            ->assertSee('Unduh File SPT');

        $previousPath = $document->file_path;
        $replacementPath = 'documents/2026/spt/spt-utama-revisi.pdf';
        Storage::disk('local')->put($replacementPath, 'file revisi pengujian');
        SptDocumentSync::sync($spt, $replacementPath, 'SPT-Utama-Revisi.pdf', $this->admin->id);

        $document->refresh();
        $this->assertSame('SPT-Utama-Revisi.pdf', $document->original_name);
        $this->assertNotSame($previousPath, $document->file_path);
        Storage::disk('local')->assertMissing($previousPath);
        Storage::disk('local')->assertExists($document->file_path);

        Livewire::test(EditSptRecord::class, ['record' => $spt->getRouteKey()])
            ->fillForm(['spt_file' => null])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSoftDeleted('documents', ['id' => $document->id]);
        Storage::disk('local')->assertExists($document->file_path);
    }

    public function test_new_documents_follow_the_configured_document_disk(): void
    {
        config(['filesystems.documents' => 'google']);
        Storage::fake('google');

        $path = 'documents/2026/laporan/laporan-gdrive.pdf';
        Storage::disk('google')->put($path, 'file laporan pengujian');

        $document = Document::query()->create([
            'year' => 2026,
            'category' => DocumentCategory::Report,
            'title' => 'Laporan pada Google Drive',
            'file_path' => $path,
            'original_name' => 'laporan-gdrive.pdf',
            'uploaded_by' => $this->admin->id,
        ]);

        $this->assertSame('google', $document->storage_disk);
        Storage::disk('google')->assertExists($document->file_path);

        $this->get(route('documents.download', $document))
            ->assertOk()
            ->assertDownload('laporan-gdrive.pdf');
    }

    public function test_inactive_user_cannot_access_panel(): void
    {
        $inactive = User::factory()->create([
            'role' => UserRole::Viewer,
            'is_active' => false,
        ]);

        $this->actingAs($inactive)
            ->get('/admin')
            ->assertForbidden();
    }
}
