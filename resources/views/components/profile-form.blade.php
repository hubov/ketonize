<div class="card text-center">
  <div class="card-header">
    <ul class="nav nav-tabs card-header-tabs" role="tablist">
      <li class="nav-item">
        <button class="nav-link active" id="diet-tab" data-bs-toggle="tab" data-bs-target="#diet" role="tab" aria-controls="diet" aria-selected="true">Diet</a>
      </li>
      <li class="nav-item">
        <button class="nav-link" id="body-tab" data-bs-toggle="tab" data-bs-target="#body" role="tab" aria-controls="body" aria-selected="false" disabled>Body</a>
      </li>
      <li class="nav-item">
        <button class="nav-link" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity" role="tab" aria-controls="activity" aria-selected="false" disabled>Activity</a>
      </li>
    </ul>
  </div>
  <div class="card-body">
    <div class="tab-content">
        <div id="diet" class="tab-pane fade show active" role="tabpanel" aria-labelledby="diet-tab">
            <h5 class="card-title">Your individual diet settings</h5>
            <p class="card-text">
                <div class="row justify-content-xxl-center">
                    <div class="col-xxl-4">
                        <h6 class="card-subtitle mt-3 mb-2 text-muted">Diet type</h6>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="diet_type" value="1" id="diet_type_vegan" disabled>
                            <label for="diet_type_vegan" class="form-check-label">Vegan classic</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="diet_type" value="2" id="diet_type_vegan_keto" checked>
                            <label for="diet_type_vegan_keto" class="form-check-label">Vegan keto</label>
                        </div>
                        <!-- <h6 class="card-subtitle my-2 text-muted">Focus</h6>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="diet_focus" value="1" id="diet_focus_health">
                            <label for="diet_focus_health" class="form-check-label">Better health condition</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="diet_focus" value="2" id="diet_focus_form">
                            <label for="diet_focus_form" class="form-check-label">Greater physical performance</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="diet_focus" value="3" id="diet_focus_mental">
                            <label for="diet_focus_mental" class="form-check-label">Greater mental abilities</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="diet_focus" value="4" id="diet_focus_hair">
                            <label for="diet_focus_hair" class="form-check-label">Hair, nails and skin condition</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="diet_focus" value="5" id="diet_focus_habits">
                            <label for="diet_focus_habits" class="form-check-label">Gain good eating habits</label>
                        </div>
                        <h6 class="card-subtitle my-2 text-muted">Physical target</h6>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="physical_target" value="1" id="physical_target_muscles">
                            <label for="physical_target_muscles" class="form-check-label">Build muscles</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="physical_target" value="1" id="physical_target_muscles">
                            <label for="physical_target_muscles" class="form-check-label">Build muscles</label>
                        </div> -->

                        <h6 class="card-subtitle mt-4 mb-2 text-muted">Diet target</h6>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="diet_target" value="1" id="diet_target_loose"
                            @if ($dietTarget == 1)
                                checked
                            @endif>
                            <label for="diet_target_loose" class="form-check-label">Loose weight</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="diet_target" value="2" id="diet_target_keep"
                            @if ($dietTarget == 2)
                                checked
                            @endif>
                            <label for="diet_target_keep" class="form-check-label">Keep weight</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="diet_target" value="3" id="diet_target_gain"
                            @if ($dietTarget == 3)
                                checked
                            @endif>
                            <label for="diet_target_gain" class="form-check-label">Gain weight</label>
                        </div>
                        <input type="button" class="btn btn-primary mt-3" id="proceed1" value="Next">
                    </div>
                </div>
            </p>
        </div>
        <div id="body" class="tab-pane fade" role="tabpanel" aria-labelledby="body-tab">
            <h5 class="card-title">Your body</h5>
            <p class="card-text">
                <div class="row justify-content-xxl-center">
                    <div class="col-xxl-4">
                        <h6 class="card-subtitle mb-2 text-muted">Gender</h6>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="gender" value="1" id="gender_female"@if ($gender == 1)
                                checked
                            @endif>
                            <label for="diet_type_vegan" class="form-check-label">Female</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="gender" value="2" id="gender_male"
                            @if ($gender == 2)
                                checked
                            @endif>
                            <label for="diet_type_vegan_keto" class="form-check-label">Male</label>
                        </div>
                        <div>
                            <label for="age" class="form-label">Birthday</label>
                            <input type="date" name="birthday" id="birthday"
                            @if ($birthday != NULL)
                                value="{{ $birthday }}"
                            @endif>
                        </div>
                        <div>
                            <label for="weight" class="form-label">Weight</label>
                            <input type="text" name="weight" id="weight"
                            @if ($weight != NULL)
                                value="{{ $weight }}"
                            @endif> kg
                        </div>
                        <div>
                            <label for="height" class="form-label">Height</label>
                            <input type="text" name="weight" id="height"
                            @if ($height != NULL)
                                value="{{ $height }}"
                            @endif> cm
                        </div>
                        <div>
                            <label for="target_weight" class="form-label">Target weight</label>
                            <input type="text" name="weight" id="target_weight"
                            @if ($targetWeight != NULL)
                                value="{{ $targetWeight }}"
                            @endif> kg
                        </div>
                        <input type="button" class="btn btn-primary mt-3" id="proceed2" value="Next">
                    </div>
                </div>
            </p>
        </div>
        <div id="activity" class="tab-pane fade" role="tabpanel" aria-labelledby="activity-tab">
            <h5 class="card-title">Your activity</h5>
            <p class="card-text">
                <div class="row justify-content-xxl-center">
                    <div class="col-xxl-4">
                        <h6 class="card-subtitle mt-3 mb-2 text-muted">Basic activity</h6>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="basic_activity" value="1" id="basic_activity_low"
                            @if ($basicActivity == 1)
                                checked
                            @endif>
                            <label for="basic_activity_low" class="form-check-label">Low (e.g. cashier, office worker)</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="basic_activity" value="2" id="basic_activity_medium"
                            @if ($basicActivity == 2)
                                checked
                            @endif>
                            <label for="basic_activity_medium" class="form-check-label">Medium (e.g. teacher, seller)</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="basic_activity" value="3" id="basic_activity_high"
                            @if ($basicActivity == 3)
                                checked
                            @endif>
                            <label for="basic_activity_high" class="form-check-label">High (e.g. waiter)</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="basic_activity" value="4" id="basic_activity_very_high"
                            @if ($basicActivity == 4)
                                checked
                            @endif>
                            <label for="basic_activity_very_high" class="form-check-label">Very high (e.g. carpenter, bicycle courier)</label>
                        </div>
                        <h6 class="card-subtitle mt-4 mb-2 text-muted">Sport activity</h6>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="sport_activity" value="1" id="sport_activity_none"
                            @if ($sportActivity == 1)
                                checked
                            @endif>
                            <label for="sport_activity_none" class="form-check-label">No activity</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="sport_activity" value="2" id="sport_activity_once"
                            @if ($sportActivity == 2)
                                checked
                            @endif>
                            <label for="sport_activity_once" class="form-check-label">1 training per week</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="sport_activity" value="3" id="sport_activity_mediun"
                            @if ($sportActivity == 3)
                                checked
                            @endif>
                            <label for="sport_activity_medium" class="form-check-label">2-3 trainings per week</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="sport_activity" value="4" id="sport_activity_high"
                            @if ($sportActivity == 4)
                                checked
                            @endif>
                            <label for="sport_activity_high" class="form-check-label">4-5 trainings per week</label>
                        </div>
                        <input type="submit" class="btn btn-primary mt-3" name="save" id="save" value="Save">
                    </div>
                </div>
            </p>
        </div>
    </div>
  </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var isEdit = {{ $edit }};
        var url = "/profile/new";
        if (isEdit == true) {
            $('#body-tab').removeAttr('disabled');
            $('#activity-tab').removeAttr('disabled');
            url = "/profile"
        }

        $('#proceed1').on('click', function() {
            $('#body-tab').removeAttr('disabled');

            var tabSelect = document.querySelector('#body-tab');
            var tab = new bootstrap.Tab(tabSelect);
            tab.show();
        });
        $('#proceed2').on('click', function() {
            $('#activity-tab').removeAttr('disabled');

            var tabSelect = document.querySelector('#activity-tab');
            var tab = new bootstrap.Tab(tabSelect);
            tab.show();
        });
        $('#save').on('click', function() {
            var formData = {
                diet_type: $('input[name=diet_type]:checked').val(),
                diet_target: $('input[name=diet_target]:checked').val(),
                gender: $('input[name=gender]:checked').val(),
                birthday: $('#birthday').val(),
                weight: $('#weight').val(),
                height: $('#height').val(),
                target_weight: $('#target_weight').val(),
                basic_activity: $('input[name=basic_activity]:checked').val(),
                sport_activity: $('input[name=sport_activity]:checked').val(),
                _token: '{{ csrf_token() }}'
            }

            console.log(formData);
            $.ajax({
                type: "POST",
                url: url,
                data: formData,
                dataType: "json",
                encode: true,
            }).done(function (data) {
                console.log('success');
                window.location = '/dashboard';
            }) .fail(function(data) {
                console.log('fail');
            });
        });
    });
</script>