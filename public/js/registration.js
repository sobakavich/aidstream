var Registration = Registration || {};

(function ($) {

    Registration = {
        request: function (url, data, type) {
            type = type || 'POST';
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('[name="_token"]').val()},
                type: type,
                url: url,
                data: data,
                success: function (data) {
                    $('.availability-check').removeClass('hidden').addClass('text-' + data.status).html(data.message);
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
                Registration.request("/check-organization-user-identifier", {userIdentifier: $(this).val()}, 'POST');
            }
        },
        changeCountry: function () {
            $('#country').change(function () {
                Registration.filterAgency($(this).val());
            });
        },
        filterAgency: function (country) {
            var filteredAgencies = '<option value="" selected="selected">Select an Agency</option>';
            for (var i in agencies) {
                if (i.indexOf(country) == 0 || i.indexOf('XI') == 0 || i.indexOf('XM') == 0) {
                    filteredAgencies += '<option value="' + i + '">' + agencies[i] + '</option>';
                }
            }
            $('#organization_registration_agency').html(filteredAgencies);
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
            Registration.disableSubmit(fieldList);
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
            Registration.disableSubmit(fieldList);
        },
        disableSubmit: function (fieldList) {
            var fields = $(fieldList.join(', '), 'form');
            fields.on('change', function () {
                var check = true;
                fields.each(function () {
                    if ($(this).val() == "") {
                        check = false;
                        return false;
                    }
                });
                if (check) {
                    $('form button[type="submit"]').removeAttr('disabled')
                } else {
                    $('form button[type="submit"]').attr('disabled', 'disabled')
                }
            });
            fields.eq(0).trigger('change');
        }
    }

})(jQuery);
