<template>
    <div class="modal fade" id="modalId" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Are you sure to Delete?</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5 class="mb-3">{{ blog.title }}</h5>
                    <p class="text-black-50">{{ blog.excerpt }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" @click="DeleteBtn" data-bs-dismiss="modal">Delete</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import {useData} from "../store/useData";
import axios from "axios";

const storeData = useData();
const props = defineProps({
    blog: {
        type: Object,
        required: true
    }
})

const DeleteBtn = async () => {
    if(props.blog && props.blog.id ){
        await axios.delete("/api/v1/"+props.blog.id)
            .then(res => {
                console.log(res)
                axios.get("/api/v1/")
                    .then(res => {
                        console.log('success',res)
                        storeData.addData(res.data)
                    })
                    .catch(error => console.log('error',error))

            })
            .catch(err => console.log(err))
    }
}
</script>
