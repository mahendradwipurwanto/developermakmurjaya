<?php

//called press-realease.json and load the json data as variable to use on below html code
$pressRelease = file_get_contents('data/press-release.json');
// check if the file is empty or not
if (!$pressRelease) {
    $pressRelease = null;
} else {
    $pressRelease = json_decode($pressRelease, true);
}

?>


<div class="container-fluid">
    <!-- start team -->
    <section class="section" id="team">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="text-center mb-5">
                        <h3 class="mb-3 fw-semibold">Our <span class="text-danger">Press Release</span></h3>
                        <p class="text-muted mb-4 ff-secondary">We are thrilled to announce the launch of our latest features, showcasing our team's unparalleled expertise and dedication. These new advancements highlight our commitment to innovation and excellence, offering our users top-tier functionality and a seamless experience.</p>
                    </div>
                </div>
            </div>
            <!-- end row -->

            <div class="row mt-4">
                <div class="col-lg-12">
                    <div>
                        <div class="timeline-2">
                            <div class="timeline-year">
                                <p class="material-shadow"><b class="fs-4">2024</b></p>
                            </div>
                            <div class="timeline-continue">
                                <?php if (!is_null($pressRelease)): ?>
                                    <?php foreach ($pressRelease as $key => $val): ?>
                                        <div class="row timeline-right">
                                            <div class="col-12">
                                                <p class="timeline-date">
                                                    <?= $val['date']; ?>
                                                </p>
                                            </div>
                                            <div class="col-12">
                                                <div class="timeline-box material-shadow">
                                                    <div class="timeline-text">
                                                        <h5><?= $val['title']; ?></h5>
                                                        <p class="text-muted"><?= $val['description']; ?></p>
                                                        <?php if (isset($val['features'])): ?>
                                                            <h6>Features release:</h6>
                                                            <?php foreach ($val['features'] as $items => $v): ?>
                                                                <div class="row g-2">
                                                                    <div class="col-sm-12">
                                                                        <div class="d-flex border border-dashed p-2 rounded position-relative">
                                                                            <div class="flex-shrink-0 avatar-xs">
                                                                                <div class="avatar-title bg-<?= $v['color']; ?>-subtle text-<?= $v['color']; ?> fs-15 rounded material-shadow-none">
                                                                                    <i class="<?= $v['icon']; ?>"></i>
                                                                                </div>
                                                                            </div>
                                                                            <div class="flex-grow-1 overflow-hidden ms-2">
                                                                                <h6 class="text-truncate mb-0"><a
                                                                                            class="stretched-link"><?= $v['title']; ?></a>
                                                                                </h6>
                                                                                <small><?= $v['description']; ?></small>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                        <?php if (isset($val['updates'])): ?>
                                                            <hr>
                                                            <h5>Change logs</h5>
                                                            <ul class="mb-0 text-muted vstack gap-2">
                                                                <?php foreach ($val['updates'] as $items => $v): ?>
                                                                    <li><?= $v; ?></li>
                                                                <?php endforeach; ?>
                                                            </ul>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>

                            <div class="timeline-year">
                                <p class="material-shadow"><span>16 June 2023</span></p>
                            </div>
                            <div class="timeline-launch">
                                <div class="timeline-box material-shadow">
                                    <div class="timeline-text">
                                        <h5>Our Activity</h5>
                                        <p class="text-muted text-capitalize mb-0">Wow...!!! What a Journey So
                                            Far...!!!</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </div>
    </section>
</div>