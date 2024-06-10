<?php

//called press-realease.json and load the json data as variable to use on below html code
$teams = file_get_contents('data/teams.json');
// check if the file is empty or not
if (!$teams) {
    $teams = null;
} else {
    $teams = json_decode($teams, true);
}

?>

<!-- start team -->
<section class="section" id="team">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5">
                    <h3 class="mb-3 fw-semibold">Our <span class="text-danger">Team</span></h3>
                    <p class="text-muted mb-4 ff-secondary">Our team stands unmatched in expertise and commitment, consistently surpassing expectations. We tackle every project with meticulous precision and unwavering excellence, ensuring top-tier results that reflect our dedication to quality and innovation.</p>
                </div>
            </div>
        </div>
        <!-- end row -->
        <div class="row">
            <?php if (!is_null($teams)): ?>
                <?php foreach ($teams as $key => $val): ?>
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="card-body text-center p-4">
                                <div class="avatar-xl mx-auto mb-4 position-relative">
                                    <img src="<?= $val['photo']; ?>" alt="" class="img-fluid rounded-circle">
                                    <a
                                            class="btn btn-success btn-sm position-absolute bottom-0 end-0 rounded-circle avatar-xs">
                                        <div class="avatar-title bg-transparent">
                                            <i class="<?= $val['icon']; ?> align-bottom"></i>
                                        </div>
                                    </a>
                                </div>
                                <!-- end card body -->
                                <h5 class="mb-1"><a class="text-body"><?= $val['name']; ?></a></h5>
                                <p class="text-muted mb-0 ff-secondary"><?= $val['position']; ?></p>
                            </div>
                        </div>
                        <!-- end card -->
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</section>
<!-- end team -->