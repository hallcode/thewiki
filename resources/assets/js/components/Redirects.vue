<template>
    <div class="form-group col-sm-7">
        <label>
            Redirects
        </label>
        <p>Add titles that will redirect users to this page.</p>
        <div class="input-group">
            <input id="new_redirect" class="form-control" v-model="new_redirect">
            <span class="input-group-btn">
                <a class="btn btn-success">
                    <i class="fa fa-fw fa-plus"></i>
                </a>
            </span>
        </div>
        <br>
        <ul>
            <li v-for="redirect in redirects">
                {{ redirect }} <a href="!#" class="red-link"><i class="fa fa-fw fa-times"></i> </a>
            </li>
        </ul>
    </ cl>
</template>

<script>
    export default {
        data: function() {
            return {
                redirects: [
                    "Test",
                    "Another Test"
                ],
                new_redirect: ""
            }
        }
    }
</script>
