<template>
    <div class="modal fade" id="updateModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Update your blog.</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div v-if="errorShow">
                        <p v-for="(error,id) in errorMessage" :key="id" class="alert alert-danger py-2">
                            <span v-for="e in error" :key="e">{{ e }}</span>
                        </p>
                    </div>
                    <form enctype="multipart/form-data">
                        <div class="row g-3">

                            <div class="col-12">
                                <label for="username" class="form-label">Blog Title</label>
                                <input type="text" v-model="storeUpdate.getBlog.title" class="form-control" id="username" placeholder="title" required>
                                <div class="invalid-feedback">
                                    Your username is required.
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="desc" class="form-label">Description</label>
                                <textarea v-model="storeUpdate.getBlog.description" id="desc" class="form-control" cols="30" rows="5" placeholder="write blog"></textarea>
                                <div class="invalid-feedback">
                                    Please enter a valid email address for shipping updates.
                                </div>
                            </div>

                            <div v-if="storeUpdate.getPhotos.length > 0" class="row my-3">
                                <div class="col-md-6">
                                    <p class="mb-2">Featured photo</p>
                                    <div class="d-flex align-items-start border-1 border-end mb-2" v-for="photo in storeUpdate.getPhotos" :key="photo.id">
                                        <button v-if="photo.file_type == 101" @click.prevent="deletePhoto( photo.id )" class="btn btn-danger btn-sm">delete</button>
                                        <img style="height: 150px" v-if="photo.file_type == 101" class="rounded img-fluid mx-2" :src="'/storage/images/'+photo.name" alt="img">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <p class="mb-2">Related photos</p>
                                    <div v-for="photo in storeUpdate.getPhotos" class="d-flex align-items-start border-1 border-bottom mb-2" :key="photo.id">
                                        <button v-if="photo.file_type == 102" @click.prevent="deletePhoto( photo.id )" class="btn btn-danger btn-sm">delete</button>
                                        <img style="height: 80px" v-if="photo.file_type == 102" class="rounded img-fluid mx-2 mb-2" :src="'/storage/related_photos/'+photo.name" alt="img">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="fe" class="form-label">Featured Photo</label>
                                <input type="file" @change="Featured" id="fe" accept="image/png, image/jpeg, image/png" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="re" class="form-label">Related photos</label>
                                <input type="file" @change="Related" id="re" accept="image/png, image/jpeg, image/png" class="form-control" multiple>
                            </div>

                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" @click.prevent="UpdateBtn" :data-bs-dismiss="closeModal ? 'modal' : ''">Update</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import {useData} from "../store/useData";
import axios from "axios";
import {computed, reactive, ref} from "vue";
import {useUpdate} from "../store/useUpdate";
const storeUpdate = useUpdate();

const storeData = useData();

// featuredPhoto
const featuredPhoto = ref();
const Featured = (e) => {
    console.log(e.target.files[0])
    if(e.target){
        for (let img of e.target.files){
            featuredPhoto.value = img
        }
    }
}

// relatedPhotos
const relatedPhotos = ref([])
const Related = (e) => {
    // console.log(e.target.files)
    for(let photo of e.target.files){
        relatedPhotos.value.push(photo)
    }
    console.log(relatedPhotos.value.length)
}

const UpdateBtn = async () => {
        let formData = new FormData();
        formData.append('image', featuredPhoto.value);

        for(let i=0; i<relatedPhotos.value.length; i++){
            formData.append('photos[]', relatedPhotos.value[i]);
        }

        let urlencoded = new URLSearchParams();
        urlencoded.append("title", storeUpdate.getBlog.title);
        urlencoded.append("description", storeUpdate.getBlog.description);


        // return console.log(formData.get('title'));
        await axios.post("/api/v1/"+storeUpdate.getBlog.id , formData )
            .then(res => {
                console.log( 'success',res)
                closeModal.value = true
                axios.get("/api/v1/")
                    .then(res => {
                        console.log('success',res)
                        storeData.addData(res.data)
                    })
                    .catch(err => {
                        console.log('error', err)

                    })

            })
            .catch(err => {
                errorShow.value = true
                closeModal.value = false
                errorMessage.value = err.response.data.errors
                alert('Could not update, plz try again.')

            })

        await axios.put("/api/v1/"+storeUpdate.getBlog.id , urlencoded )
            .then(res => {
                console.log( 'success',res)
                closeModal.value = true
                axios.get("/api/v1/")
                    .then(res => {
                        console.log('success',res)
                        storeData.addData(res.data)
                    })
                    .catch(err => {
                        console.log('error', err)
                    })
            })
            .catch(err => {
                errorShow.value = true
                closeModal.value = false
                errorMessage.value = err.response.data.errors
                alert('Could not update, plz try again.')
            })

}

//delete photo
const deletePhoto = async (id) => {
    const updatePhotos = storeUpdate.getPhotos.filter( photo => photo.id != id)
    storeUpdate.addPhotos(updatePhotos)

    await axios.delete("/api/v1/photo-delete/"+id)
        .then(res => {
            console.log('success', res)
            axios.get("/api/v1/")
                .then(res => {
                    console.log('success',res)
                    storeData.addData(res.data)
                })
                .catch(error => console.log('error',error))
        })
        .catch(err => {
            console.log('error', err.response.data.errors)

        })
}

// for error message
const closeModal = ref(true)
const errorMessage = ref()
const errorShow = ref(false)
</script>
<style scoped>
.modal-dialog{
    max-width: 600px !important;
}
</style>
