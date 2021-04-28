<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#35A768">

    <title>Book a Numbas office hours appointment</title>

    <link rel="stylesheet" type="text/css" href="<?= asset_url('assets/ext/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= asset_url('assets/ext/jquery-ui/jquery-ui.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= asset_url('assets/ext/cookieconsent/cookieconsent.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= asset_url('assets/css/frontend.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= asset_url('assets/css/general.css') ?>">

    <link rel="icon" type="image/x-icon" href="<?= asset_url('assets/img/favicon.ico') ?>">
    <link rel="icon" sizes="192x192" href="<?= asset_url('assets/img/logo.png') ?>">

    <script src="<?= asset_url('assets/ext/fontawesome/js/fontawesome.min.js') ?>"></script>
    <script src="<?= asset_url('assets/ext/fontawesome/js/solid.min.js') ?>"></script>
</head>

<body>
    <div class="container">
        <div class="row">
            <header class="col-12">
                <h1>Numbas office hours</h1>
            </header>
            <div class="col-12" id="service-description">
            </div>
            <main class="col-12">
                <form id="book-appointment-form" method="post" class="row"> 

                    <div class="col-12 col-lg-8">
                        <div class="form-group">
                                <?php
                                // Group services by category, only if there is at least one service with a parent category.
                                $has_category = FALSE;
                                foreach ($available_services as $service)
                                {
                                    if ($service['category_id'] != NULL)
                                    {
                                        $has_category = TRUE;
                                        break;
                                    }
                                }

                                if (count($available_services)==1) {
                                    ?><input type="hidden" id="select-service" name="service" value="<?= $available_services[0]['id'] ?>"> <?php
                                } else {
                                    ?>
                                    <label for="select-service">
                                        <strong><?= lang('service') ?></strong>
                                    </label>

                                    <select id="select-service" class="form-control">
                                        <?php foreach ($available_services as $service) { ?>
                                        <option value="<?= $service['id'] ?>"><?= $service['name'] ?></option>
                                        <?php }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name" class="control-label">
                                Your name
                            </label>
                            <input type="text" id="name" name="name" class="required form-control" required/>
                        </div>
                        <div class="form-group">
                            <label for="email" class="control-label">
                                Your email address
                            </label>
                            <input type="email" id="email" name="email" class="required form-control" required/>
                            <div class="invalid-feedback">Please enter a valid email address, such as <code>you@domain.tld</code>.</div>
                            <small class="form-text text-muted" id="notes-help">
                                <p>An invitation link for the meeting will be sent to this address.</p>
                            </small>
                        </div>
                        <div class="form-group">
                            <label for="notes" class="control-label">
                                What would you like to discuss in the session?
                            </label>
                            <textarea id="notes" name="notes" maxlength="500" class="form-control" rows="1" aria-describedby="notes-help" required></textarea>
                            <small class="form-text text-muted" id="notes-help">
                                <p>This field will help me prepare for the meeting.</p>
                                <p>You can ask for help writing questions, using Numbas with your VLE, or a more general chat about any topic to do with Numbas.</p>
                            </small>
                        </div>
                    </div>

                    <div class="col-12 col-lg-4">
                            <div class="row frame-content">
                                <div class="col-12 col-lg-12 col-md-6">
                                    <div class="form-group">
                                        <label for="select-date">Date</label>
                                        <div id="select-date"></div>
                                        <input type="hidden" name="date" id="date" required>
                                        <div class="invalid-feedback">Please select a date.</div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-12 col-md-6">
                                    <div class="form-group">
                                        <label for="available-hours">Time</label>
                                        <select class="form-control" id="available-hours" name="hour"></select>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <button id="book-appointment-submit" type="button" class="mx-auto btn btn-primary mb-3 btn-block">
                        <i class="fas fa-check-square mr-2"></i>
                        <?= ! $manage_mode ? lang('confirm') : lang('update') ?>
                    </button>
                    <?php if ($manage_mode): ?>
                    <button id="cancel-appointment" class="mx-auto btn btn-warning">Cancel this appointment</button>
                    <button id="delete-personal-information" class="mx-auto btn btn-danger">Delete all of your data</button>
                    <?php endif; ?>

                    <input type="hidden" name="csrfToken"/>
                    <input id="select-provider" name="provider" type="hidden">
                    <input id="select-timezone" name="timezone" type="hidden">
                </form>
                <?php if ($manage_mode): ?>
                        <form id="cancel-appointment-form" method="post" class="mx-auto"
                              action="<?= site_url('appointments/cancel/' . $appointment_data['hash']) ?>">

                            <input type="hidden" name="csrfToken" value="<?= $this->security->get_csrf_hash() ?>"/>

                            <textarea name="cancel_reason" style="display:none"></textarea>

                        </form>
                    <?php endif; ?>
            </main>
            <footer class="text-muted col-12 mt-5">
                <p><a href="https://numbas.org.uk">Numbas</a>, School of Mathematics, Statistics & Physics, Herschel Building, Newcastle University, NE1 7RU, United Kingdom</p>
            </footer>
        </div>
    </div>


<?php if ($display_cookie_notice === '1'): ?>
    <?php require 'cookie_notice_modal.php' ?>
<?php endif ?>

<?php if ($display_terms_and_conditions === '1'): ?>
    <?php require 'terms_and_conditions_modal.php' ?>
<?php endif ?>

<?php if ($display_privacy_policy === '1'): ?>
    <?php require 'privacy_policy_modal.php' ?>
<?php endif ?>

<script>
    var GlobalVariables = {
        availableServices: <?= json_encode($available_services) ?>,
        availableProviders: <?= json_encode($available_providers) ?>,
        baseUrl: <?= json_encode(config('base_url')) ?>,
        manageMode: <?= $manage_mode ? 'true' : 'false' ?>,
        customerToken: <?= json_encode($customer_token) ?>,
        dateFormat: <?= json_encode($date_format) ?>,
        timeFormat: <?= json_encode($time_format) ?>,
        firstWeekday: <?= json_encode($first_weekday) ?>,
        displayCookieNotice: <?= json_encode($display_cookie_notice === '1') ?>,
        appointmentData: <?= json_encode($appointment_data) ?>,
        providerData: <?= json_encode($provider_data) ?>,
        customerData: <?= json_encode($customer_data) ?>,
        displayAnyProvider: <?= json_encode($display_any_provider) ?>,
        csrfToken: <?= json_encode($this->security->get_csrf_hash()) ?>
    };

    var EALang = <?= json_encode($this->lang->language) ?>;
    var availableLanguages = <?= json_encode(config('available_languages')) ?>;
</script>

<script src="<?= asset_url('assets/js/general_functions.js') ?>"></script>
<script src="<?= asset_url('assets/ext/jquery/jquery.min.js') ?>"></script>
<script src="<?= asset_url('assets/ext/jquery-ui/jquery-ui.min.js') ?>"></script>
<script src="<?= asset_url('assets/ext/cookieconsent/cookieconsent.min.js') ?>"></script>
<script src="<?= asset_url('assets/ext/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= asset_url('assets/ext/popper/popper.min.js') ?>"></script>
<script src="<?= asset_url('assets/ext/tippy/tippy-bundle.umd.min.js') ?>"></script>
<script src="<?= asset_url('assets/ext/datejs/date.min.js') ?>"></script>
<script src="<?= asset_url('assets/ext/moment/moment.min.js') ?>"></script>
<script src="<?= asset_url('assets/ext/moment/moment-timezone-with-data.min.js') ?>"></script>
<script src="<?= asset_url('assets/js/frontend_book_api.js') ?>"></script>
<script src="<?= asset_url('assets/js/frontend_book.js') ?>"></script>

<script>
    $(function () {
        FrontendBook.initialize(true, GlobalVariables.manageMode);
        GeneralFunctions.enableLanguageSelection($('#select-language'));
    });
</script>

<?php google_analytics_script(); ?>
</body>
</html>
