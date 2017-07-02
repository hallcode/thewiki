<template>
    <div class="form-group col-sm-7">
        <label>
            Redirects
        </label>
        <p class="help-block">Add titles that will redirect users to this page.</p>
        <div class="input-group">
            <input type="text" id="new_redirect" class="form-control" v-model="new_redirect" @keyup.enter.prevent="addNewRedirect()">
            <span class="input-group-btn">
                <a id="saveRedirect" class="btn btn-success" @click="addNewRedirect()">
                    <i class="fa fa-fw fa-plus"></i>
                </a>
            </span>
        </div>
        <p></p>
        <ul>
            <li v-for="(redirect, index) in redirects">
                {{ redirect.title }} <a href="#!" @click="deleteRedirect(index)" class="red-link"><i class="fa fa-fw fa-times"></i> </a>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        props: [
            "pageId"
        ],
        data: function() {
            return {
                redirects: [
                ],
                new_redirect: ""
            }
        },
        methods: {
            addNewRedirect: function() {
                var self = this;
                $('#saveRedirect').prop('disabled', true);

                $.post('/ajax/redirect/'+this.pageId+'/'+this.new_redirect).done(function(data, status) {
                    var newRedir = JSON.parse(data);
                    self.redirects.push({
                        "id": newRedir.id,
                        "title": newRedir.title,
                    });
                })

                this.new_redirect = "";
                $('#saveRedirect').prop('disabled', false);
            },
            deleteRedirect: function(index) {
                var redirect = this.redirects[index];
                this.redirects.splice(index, 1);

                if (redirect.id !== null)
                {
                    $.post('/ajax/redirect/delete/'+redirect.id);
                }
            }
        },
        mounted: function() {
            var self = this;

            $.get('/ajax/redirect/'+this.pageId).done(function(data) {
                $.each(JSON.parse(data), function(index, value) {
                    self.redirects.push({
                        "id": value.id,
                        "title": value.title,
                    });
                });
            });
        }
    }
</script>
