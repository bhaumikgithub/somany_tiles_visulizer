<template>
    <div class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">{{ caption }}</h4>
                </div>

                <div class="modal-body">
                    <p>{{ message }}</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="{{ onConfirm }}">Confirm</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                caption: 'Confirm ',
                message: 'Please confirm.',
                onConfirm: window.$('#roomsForm').submit(),
            }
        }
    }
</script>
