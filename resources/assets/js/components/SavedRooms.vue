<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Example Component</div>

                    <div class="panel-body">

                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th colspan="2">Room</th>
                                    <th colspan="2">Url</th>
                                    <th>Note</th>
                                    <th colspan="3">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="item in items">
                                    <td class="table-text">{{ item.id }}</td>
                                    <td class="table-text">{{ item.name }}</td>
                                    <td class="table-text">{{ item.file }}</td>
                                    <td class="table-text">{{ item.url }}</td>
                                    <td class="table-text">{{ item.shape }}</td>
                                    <td class="table-text">{{ item.expProps }}</td>
                                    <td class="table-text">{{ item.enabled }}</td>
                                </tr>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                items: [],
            };
        },

        mounted() {
            axios.get('/get/tiles')
                .then(response => this.items = response.data)
                .catch(error => console.log(error));
                // .then(() => {
                //     console.log('done');
                // });
        }
    }
</script>
