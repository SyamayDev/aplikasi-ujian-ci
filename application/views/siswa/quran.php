<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <title>Al-Qur'an Digital</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lateef&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/fitur_islami.css') ?>">
</head>
<body>
    <div id="wrapper">
        <div id="content">
            <header class="main_haeder multi_item bg-success text-white shadow">
                <div class="em_side_right">
                    <a class="btn btn__back rounded-circle bg-light text-success" href="<?= base_url('siswa/beranda') ?>">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
                <div class="title_page">
                    <span class="page_name">Al-Qur'an Digital</span>
                </div>
                <div class="em_side_right"></div>
            </header>

            <main id="app" class="container mt-4 animate__animated animate__fadeIn">
                <!-- Fitur Terakhir Dibaca -->
                <div v-if="lastRead" class="alert alert-info alert-dismissible fade show animate__animated animate__bounceIn" role="alert" @click="goToLastRead" style="cursor: pointer; user-select: none;">
                    Terakhir Baca: Surat {{ lastRead.surahName }} - Ayat {{ lastRead.ayatNumber }}
                    <button type="button" class="btn-close" @click.stop="clearLastRead" aria-label="Close"></button>
                </div>

                <div class="card my-3 border-0 shadow-sm animate__animated animate__zoomIn">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Daftar Surah</span>
                    </div>
                    <div class="card-body">
                        <div class="search-container position-relative mb-3">
                            <input type="text" class="form-control pe-5" id="surahSearch" v-model="searchQuery" placeholder="Cari surah..." @input="filterSurahs">
                            <button class="btn btn-success voice-search-btn" @click="startVoiceSearch">
                                <i class="fas fa-microphone"></i>
                            </button>
                        </div>
                        <div v-if="loading" class="d-flex justify-content-center">
                            <div class="spinner"></div>
                        </div>
                        <div v-else class="surah-list">
                            <div v-for="data in paginatedSurahs" :key="data.nomor" class="surah-card" @click="openDetailModal(data.nomor)">
                                <div class="arabic-name">{{ data.nama }}</div>
                                <div class="latin-name">{{ data.nama_latin }}</div>
                                <div class="translation">{{ data.arti }}</div>
                                <div class="verses">{{ data.jumlah_ayat }} Ayat</div>
                            </div>
                        </div>
                        <div class="pagination mt-3 d-flex justify-content-center align-items-center">
                            <button class="btn btn-outline-success btn-sm" :disabled="currentPage === 1" @click="previousPage">Previous</button>
                            <span class="page-number mx-2 badge bg-light text-dark border">{{ currentPage }} / {{ totalPages }}</span>
                            <button class="btn btn-outline-success btn-sm" :disabled="currentPage === totalPages" @click="nextPage">Next</button>
                        </div>
                    </div>
                </div>

                <!-- Modal Detail Surah (dengan atribut Bootstrap 5) -->
                <div class="modal fade" id="detailModal" data-bs-backdrop="false">
                    <div class="modal-dialog modal-xl modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title fs-4">{{ detail.nama_latin }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div v-if="loading2" class="d-flex justify-content-center">
                                    <div class="spinner"></div>
                                </div>
                                <div v-else>
                                    <div class="row p-2">
                                        <div class="col-lg-12">
                                            <div v-if="detail.nomor !== 1" class="text-center mb-3" style="font-family: 'Lateef', serif; font-size: 2rem; color: var(--color-green);">
                                                بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ
                                            </div>
                                            <div class="border border-success p-3" style="max-height: 60vh; overflow-y: scroll; border-radius: 8px;">
                                                <div v-for="(ayat, idx) in ayats" :key="ayat.nomor" :id="'ayat-' + ayat.nomor">
                                                    <p><span class="badge bg-success">Ayat {{ ayat.nomor }}</span></p>
                                                    <p class="arabic-text text-end">{{ ayat.ar }}</p>
                                                    <p>
                                                        <small v-html="(ayat.tr && ayat.tr.trim()) ? ayat.tr : '(Transliterasi tidak tersedia)'"></small><br>
                                                        <small class="text-muted fst-italic">{{ (ayat.id && ayat.id.trim()) ? ayat.id : '(Terjemahan tidak tersedia)' }}</small>
                                                    </p>
                                                    <div class="mt-2">
                                                        <button class="btn btn-sm btn-outline-primary" @click="togglePlay(ayat, idx)">
                                                            <i v-if="currentAudioIndex === idx && isPlaying" class="fas fa-stop"></i>
                                                            <i v-else class="fas fa-play"></i>
                                                            <span v-if="currentAudioIndex === idx && isPlaying"> Stop</span>
                                                            <span v-else> Play</span>
                                                        </button>
                                                        <!-- Fitur Tandai Terakhir Dibaca ditambahkan di sini -->
                                                        <button class="btn btn-sm btn-outline-info ms-2" @click="toggleAyatBookmark(ayat)">
                                                            <i :class="{'fas fa-bookmark': isAyatBookmarked(detail.nomor, ayat.nomor), 'far fa-bookmark': !isAyatBookmarked(detail.nomor, ayat.nomor)}"></i> Tandai
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-success ms-2" @click="shareAyat(ayat)">
                                                            <i class="fas fa-share-alt"></i> Bagikan
                                                        </button>
                                                    </div>
                                                    <hr>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Voice Search Popup -->
                <div v-if="showVoicePopup" class="voice-popup">
                    <p>{{ voiceText }}</p>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS (diperlukan untuk modal) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- JQuery (diperlukan untuk Select2) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue-resource@1.5.1"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="<?= base_url('assets/js/quran.js') ?>"></script>
</body>
</html>