<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <!-- Brand Logo -->
    <a href="<?= site_url('dashboard') ?>" class="brand-link">
        <img src="<?= base_url('assets/images/logo_pulo_gebang.jpg') ?>"
             alt="TTPG Logo"
             class="brand-image img-circle elevation-3"
             style="opacity: .9; background: #fff; padding: 2px;">
        <span class="brand-text font-weight-bold">TTPG AUDIO</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

         <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">

                <!-- DASHBOARD -->
                <li class="nav-item">
                    <a href="<?= site_url('dashboard') ?>" 
                       class="nav-link <?= $this->uri->segment(1) == 'dashboard' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Dashboard Utama</p>
                    </a>
                </li>

<?php $role = $this->session->userdata('role'); ?>

<?php if ($role === 'admin'): ?>

                <!-- MONITORING SECTION -->
                <li class="nav-header text-uppercase text-xs font-weight-bold">📡 Monitoring & Display</li>
                
                <li class="nav-item">
                    <a href="<?= site_url('bus_monitor/tv_display') ?>" 
                       class="nav-link <?= $this->uri->segment(2) == 'tv_display' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-desktop"></i>
                        <p>Semua Area (Global)</p>
                    </a>
                </li>

                <li class="nav-item <?= in_array($this->uri->segment(2), ['tv_masuk', 'tv_keberangkatan', 'tv_pengendapan', 'tv_keluar']) ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-tv"></i>
                        <p>
                            Monitor Per Area
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= site_url('bus_monitor/tv_masuk') ?>" class="nav-link <?= $this->uri->segment(2) == 'tv_masuk' ? 'active' : '' ?>" target="_blank">
                                <i class="fas fa-sign-in-alt nav-icon text-sm"></i>
                                <p>TV Bus Masuk</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('bus_monitor/tv_keberangkatan') ?>" class="nav-link <?= $this->uri->segment(2) == 'tv_keberangkatan' ? 'active' : '' ?>" target="_blank">
                                <i class="fas fa-plane-departure nav-icon text-sm"></i>
                                <p>TV Keberangkatan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('bus_monitor/tv_kedatangan') ?>" class="nav-link <?= $this->uri->segment(2) == 'tv_kedatangan' ? 'active' : '' ?>" target="_blank">
                                <i class="fas fa-plane-departure nav-icon text-sm"></i>
                                <p>TV Kedatangan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('bus_monitor/tv_pengendapan') ?>" class="nav-link <?= $this->uri->segment(2) == 'tv_pengendapan' ? 'active' : '' ?>" target="_blank">
                                <i class="fas fa-parking nav-icon text-sm"></i>
                                <p>TV Pengendapan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('bus_monitor/tv_keluar') ?>" class="nav-link <?= $this->uri->segment(2) == 'tv_keluar' ? 'active' : '' ?>" target="_blank">
                                <i class="fas fa-external-link-alt nav-icon text-sm"></i>
                                <p> TV Pintu Keluar</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- OPERATIONAL SECTION -->
                <li class="nav-header text-uppercase text-xs font-weight-bold">🚌 Operasional Bus</li>

                <li class="nav-item <?= in_array($this->uri->segment(2), ['kedatangan', 'pengendapan', 'keberangkatan', 'pintu_keluar', 'masuk']) ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= in_array($this->uri->segment(2), ['kedatangan', 'pengendapan', 'keberangkatan', 'pintu_keluar', 'masuk']) ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-bus-alt"></i>
                        <p>
                            Input Data Bus
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= site_url('bus_monitor/masuk') ?>" 
                               class="nav-link <?= $this->uri->segment(2) == 'masuk' ? 'active' : '' ?>">
                                <i class="fas fa-download nav-icon text-sm"></i>
                                <p>Bus Masuk</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('bus_monitor/kedatangan') ?>" 
                               class="nav-link <?= $this->uri->segment(2) == 'kedatangan' ? 'active' : '' ?>">
                                <i class="fas fa-download nav-icon text-sm"></i>
                                <p>Kedatangan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('bus_monitor/pengendapan') ?>" 
                               class="nav-link <?= $this->uri->segment(2) == 'pengendapan' ? 'active' : '' ?>">
                                <i class="fas fa-hourglass-half nav-icon text-sm"></i>
                                <p>Pengendapan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('bus_monitor/keberangkatan') ?>" 
                               class="nav-link <?= $this->uri->segment(2) == 'keberangkatan' ? 'active' : '' ?>">
                                <i class="fas fa-upload nav-icon text-sm"></i>
                                <p>Keberangkatan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('bus_monitor/pintu_keluar') ?>" 
                               class="nav-link <?= $this->uri->segment(2) == 'pintu_keluar' ? 'active' : '' ?>">
                                <i class="fas fa-door-open nav-icon text-sm"></i>
                                <p>Pintu Keluar</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- MEDIA SECTION -->
                <li class="nav-header text-uppercase text-xs font-weight-bold">🔊 Sistem Pengumuman</li>
                
                <li class="nav-item">
                    <a href="<?= site_url('audio') ?>" class="nav-link <?= $this->uri->segment(1) == 'audio' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-bullhorn"></i>
                        <p>Audio System</p>
                        <span class="right badge badge-danger shadow-sm">Antrian</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= site_url('manajemen_iklan') ?>" class="nav-link <?= $this->uri->segment(1) == 'manajemen_iklan' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-calendar-alt"></i>
                        <p>Manajemen Iklan</p>
                    </a>
                </li>

                <!-- MASTER DATA SECTION -->
                <li class="nav-header text-uppercase text-xs font-weight-bold">⚙️ Master Data</li>
                <li class="nav-item">
                    <a href="<?= site_url('dashboard/kapasitas') ?>" class="nav-link <?= ($this->uri->segment(1) == 'dashboard' && $this->uri->segment(2) == 'kapasitas') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-warehouse"></i>
                        <p>Kapasitas Area</p>
                    </a>
                </li>

                <!-- SECURITY SECTION -->
                <li class="nav-header text-uppercase text-xs font-weight-bold">🛡️ Sistem & Keamanan</li>
                <li class="nav-item">
                    <a href="<?= site_url('users') ?>" class="nav-link <?= $this->uri->segment(1) == 'users' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-users-cog"></i>
                        <p>Manajemen User</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('log') ?>" class="nav-link <?= $this->uri->segment(1) == 'log' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-history"></i>
                        <p>Log Aktivitas</p>
                    </a>
                </li>

<?php else: ?>
                <li class="nav-header text-uppercase text-xs font-weight-bold">🚌 Operasional Bus</li>

                <?php if ($role === 'petugas_masuk'): ?>
                <li class="nav-item">
                    <a href="<?= site_url('bus_monitor/masuk') ?>" class="nav-link <?= $this->uri->segment(2) == 'masuk' ? 'active' : '' ?>">
                        <i class="fas fa-download nav-icon text-sm"></i>
                        <p>Bus Masuk</p>
                    </a>
                </li>
                <?php endif; ?>

                <?php if ($role === 'petugas_kedatangan'): ?>
                <li class="nav-item">
                    <a href="<?= site_url('bus_monitor/kedatangan') ?>" class="nav-link <?= $this->uri->segment(2) == 'kedatangan' ? 'active' : '' ?>">
                        <i class="fas fa-download nav-icon text-sm"></i>
                        <p>Kedatangan</p>
                    </a>
                </li>
                <?php endif; ?>

                <?php if ($role === 'petugas_pengendapan'): ?>
                <li class="nav-item">
                    <a href="<?= site_url('bus_monitor/pengendapan') ?>" class="nav-link <?= $this->uri->segment(2) == 'pengendapan' ? 'active' : '' ?>">
                        <i class="fas fa-hourglass-half nav-icon text-sm"></i>
                        <p>Pengendapan</p>
                    </a>
                </li>
                <?php endif; ?>

                <?php if ($role === 'petugas_keberangkatan'): ?>
                <li class="nav-item">
                    <a href="<?= site_url('bus_monitor/keberangkatan') ?>" class="nav-link <?= $this->uri->segment(2) == 'keberangkatan' ? 'active' : '' ?>">
                        <i class="fas fa-upload nav-icon text-sm"></i>
                        <p>Keberangkatan</p>
                    </a>
                </li>
                <?php endif; ?>

                <?php if ($role === 'petugas_keluar'): ?>
                <li class="nav-item">
                    <a href="<?= site_url('bus_monitor/pintu_keluar') ?>" class="nav-link <?= $this->uri->segment(2) == 'pintu_keluar' ? 'active' : '' ?>">
                        <i class="fas fa-door-open nav-icon text-sm"></i>
                        <p>Pintu Keluar</p>
                    </a>
                </li>
                <?php endif; ?>

<?php endif; ?>

                <li class="nav-item mt-4">
                    <a href="<?= site_url('auth/logout') ?>" class="nav-link bg-danger">
                        <i class="nav-icon fas fa-power-off"></i>
                        <p>Logout</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>
