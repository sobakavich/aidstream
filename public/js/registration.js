var Registration = Registration || {};

(function ($) {

    Registration = {
        request: function (url, data, callback, type) {
            type = type || 'POST';
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('[name="_token"]').val()},
                type: type,
                url: url,
                data: data,
                success: function (data) {
                    if (typeof callback == 'function') callback(data);
                }
            });
        },
        abbrGenerator: function () {
            var ignoreList = ['and', 'of', 'the', 'an', 'a'];

            function getWordList(name) {
                var nameArray = name.split(/\ +/g);
                return nameArray.filter(function (value) {
                    return $.inArray(value.toLowerCase(), ignoreList) === -1;
                })
            }

            function getAbbr(wordList) {
                var abbr = '';
                for (var i in wordList) {
                    var word = wordList[i];
                    abbr += word.substr(0, 1);
                }
                return abbr.toLowerCase();
            }

            $('#organization_name').change(function () {
                if ($.trim($('#organization_name_abbr').val()) != "") {
                    return false;
                }
                var name = $(this).val();
                var wordList = getWordList(name);
                var abbr = getAbbr(wordList);
                $('#organization_name_abbr').val(abbr).trigger('keydown').trigger('change');
            });
        },
        checkAbbrAvailability: function () {
            var checkElem = $('[name="organization_name_abbr"]');
            checkElem.on('change', checkAvailability);
            checkElem.on('keydown', function () {
                $('.availability-check').html('').addClass('hidden').removeClass('text-success text-danger');
            });
            function checkAvailability() {
                var userIdentifier = $(this).val();
                if ($.trim(userIdentifier) == "") {
                    return false;
                }
                var callback = function (data) {
                    $('.availability-check').removeClass('hidden').addClass('text-' + data.status).html(data.message);
                    $(this).parents('.has-error').removeClass('has-error');
                    $(this).siblings('.text-danger').remove();
                };
                Registration.request("/check-organization-user-identifier", {userIdentifier: $(this).val()}, callback);
            }
        },
        changeCountry: function () {
            $('#country').change(function () {
                Registration.filterAgency($(this).val());
            });
            $('#country').trigger('change');
        },
        filterAgency: function (country) {
            var filteredAgencies = '<option value="" selected="selected">Select an Agency</option>';
            var selected = '';
            for (var i in agencies) {
                if (i.indexOf(country) == 0 || i.indexOf('XI') == 0 || i.indexOf('XM') == 0) {
                    filteredAgencies += '<option value="' + i + '">' + agencies[i] + '</option>';
                    if (i == $('#agencies').attr('data-agency')) {
                        selected = i;
                    }
                }
            }
            $('#organization_registration_agency').html(filteredAgencies).val(selected);
        },
        regNumber: function () {
            $('#country, #organization_registration_agency, #registration_number').on('keyup change', function () {
                var identifier = '';
                var value = '';
                if ($('#country').val() == '' || $('#organization_registration_agency').val() == '' || $('#registration_number').val() == '') {
                    identifier = '[Registration Agency]-[Registration Number]';
                    value = "";
                } else {
                    identifier = value = $('#organization_registration_agency').val() + '-' + $('#registration_number').val();
                }

                $('#org_identifier').html(identifier);
                $('#organization_identifier').val(value);
            });
            $('#registration_number').trigger('change');
        },
        usernameGenerator: function () {
            $('.user-blocks').delegate('.username', 'change keyup', function (e) {
                var username = $(this).val();
                if (userIdentifier.indexOf(username) == 0 && username.length <= userIdentifier.length) {
                    username = "";
                } else if (username.indexOf(userIdentifier) != 0) {
                    username = userIdentifier + username;
                }
                $(this).next('.login_username').val(username);
            });
        },
        addUser: function () {
            $('#add-user').click(function () {
                var index = 0;
                if ($('.user-blocks .user-block').length > 0) {
                    var name = $('.user-blocks .user-block:last-child .form-control:first').attr('name');
                    index = parseInt(name.match(/[\d]+/g)) + 1;
                }
                var template = $('#user_template').clone();
                var html = template.html();
                html = html.replace(/_index_/g, index);
                $('.user-blocks').append(html);
                $(this).html('Add Another User');
                Registration.disableUsersSubmitButton();
            });
        },
        removeUser: function () {
            $('.user-blocks').delegate('.delete', 'click', function (e) {
                e.preventDefault();
                var _this = $(this);

                if ($('#removeDialog').length === 0) {
                    $('body').append('' +
                        '<div class="modal" id="removeDialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 9999">' +
                        '<div class="modal-dialog">' +
                        '<div class="modal-content">' +
                        '<div class="modal-header">' +
                        '<h4 class="modal-title" id="myModalLabel"></h4>' +
                        '</div>' +
                        '<div class="modal-body"></div>' +
                        '<div class="modal-footer"></div>' +
                        '</div>' +
                        '</div>' +
                        '</div>');
                }

                var removeDialog = $('#removeDialog');

                var buttons = '' +
                    '<button class="btn btn-primary btn_remove" type="button">Yes</button>' +
                    '<button class="btn btn-default" type="button"  data-dismiss="modal">No</button>';

                $('.modal-header .modal-title', removeDialog).html('Remove Confirmation');
                $('.modal-body', removeDialog).html('Are you sure you want to remove this block?');
                $('.modal-footer', removeDialog).html(buttons);

                $('body').undelegate('.btn_remove', 'click').delegate('.btn_remove', 'click', function () {
                    _this.parent('.user-block').remove();
                    if ($('.user-blocks .user-block').length == 0) {
                        $('#add-user').html('Add a User');
                    }
                    removeDialog.modal('hide');
                    Registration.disableUsersSubmitButton();
                });

                removeDialog.modal('show');
            });
        },
        disableOrgSubmitButton: function () {
            var fieldList = [
                '#organization_name',
                '#organization_name_abbr',
                '#organization_type',
                '#organization_address',
                '#country',
                '#organization_registration_agency',
                '#registration_number',
                '#organization_identifier'
            ];
            Registration.disableSubmit(fieldList, '#organization-form');
        },
        disableUsersSubmitButton: function () {
            var fieldList = [
                '#first_name',
                '#last_name',
                '#email',
                '#password',
                '#confirm_password',
                '#secondary_contact',
                '[name$="[username]"]',
                '[name$="[email]"]',
                '[name$="[first_name]"]',
                '[name$="[last_name]"]',
                '[name$="[role]"]'
            ];
            Registration.disableSubmit(fieldList, '#users-form');
        },
        disableSubmit: function (fieldList, form) {
            var fields = $(fieldList.join(', '), form);
            fields.on('change', function () {
                var check = true;
                fields.each(function () {
                    if ($(this).val() == "") {
                        check = false;
                        return false;
                    }
                });
                if (check) {
                    $('button[type="submit"]', form).removeAttr('disabled')
                } else {
                    $('button[type="submit"]', form).attr('disabled', 'disabled')
                }
            });
            fields.eq(0).trigger('change');
        },
        filterSimilarOrg: function () {
            $('select').select2({
                tags: true
            }).change(function () {
                var value = $(this).val();
                if (isNaN(value)) {
                    $('body').append('<div class="loader">.....</div>');
                    $.ajax({
                        type: 'get',
                        url: '/similar-organizations/' + value,
                        success: function (data) {
                            var list = '';
                            for (var i in data) {
                                list += '<li><input name="similar_organization" type="checkbox" value="' + i + '"> ' + data[i] + '</li>';
                            }
                            $('ul.organization-list').html(list);
                        },
                        complete: function () {
                            $('body > .loader').addClass('hidden').remove();
                        }
                    });
                } else {
                    $('ul.organization-list').html('');
                }
            });

            $('form').delegate('[name="similar_organization"]', 'click', function () {
                $('[name="similar_organization"]').not(this).prop('checked', false);
            });
        },
        addRegAgency: function () {
            var modal = $('#reg_agency');
            $('.add_agency').click(function () {
                modal.modal('show');
            });

            modal.on('show.bs.modal', function () {
                var country = $('#country').val();
                if (country == "") {
                    $('.messages', modal).removeClass('hidden').html('Please select a Country to add Registration Agency.');
                    $('button[type="submit"]', this).addClass('hidden');
                } else {
                    $('.form-container', modal).removeClass('hidden');
                    $('button[type="submit"]', this).removeClass('hidden');
                }
            });

            modal.on('hidden.bs.modal', function () {
                $('.messages, .form-container', '#reg_agency').addClass('hidden');
            });

            $.validator.addMethod("abbr", function (value, element, param) {
                if (this.optional(element)) {
                    return true;
                }
                return /^[A-Z]+$/.test(value);
            });

            $.validator.addMethod("abbr_exists", function (value, element, param) {
                var regAgency = $('#country').val() + '-' + value;
                return !(regAgency in agencies);
            });

            var form = $('#reg-agency-form');
            form.validate({
                submitHandler: function () {
                    var country = $('#country').val();
                    var name = $('#name', form).val();
                    var shortForm = $('#short_form', form).val();
                    var website = $('#website', form).val();
                    var regAgency = $('#organization_registration_agency');
                    var agencyData = JSON.parse($('#agencies').val());
                    var agencyCode = country + '-' + shortForm;
                    agencyData[agencyCode] = name;
                    agencies = agencyData;
                    $('#agency_name').val(name);
                    $('#agency_website').val(website);
                    $('#agencies').val(JSON.stringify(agencyData));
                    modal.modal('hide');
                    $('#country').trigger('change');
                    regAgency.val(agencyCode).trigger('change');

                    var newAgencies = $('#new_agencies').val();
                    newAgencies = newAgencies == '' ? {} : JSON.parse(newAgencies);
                    newAgencies[agencyCode] = {name: name, short_form: shortForm, website: website};
                    $('#new_agencies').val(JSON.stringify(newAgencies));
                }
            });
            form.submit(function () {
                $('button[type="submit"]', this).removeAttr('disabled');
            });
            $('#name', form).rules('add', {required: true, messages: {required: 'Name is required.'}});
            $('#short_form', form).rules('add', {
                required: true,
                abbr: true,
                abbr_exists: true,
                messages: {required: 'Short Form is required.', abbr: 'Short Form should be alphabetic uppercase characters.', abbr_exists: 'Registration Agency with this short form already exists.'}
            });
            $('#website', form).rules('add', {required: true, url: true, messages: {required: 'Website is required.', url: 'Website is not a valid URL.'}});

        }
    }

})(jQuery);
