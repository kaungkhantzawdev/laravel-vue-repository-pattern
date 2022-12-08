import {defineStore} from "pinia";
export const useUpdate = defineStore('useUpdate', {
    state: () => ({
        blog: '',
        photos: ''
    }),
    getters: {
        getBlog: (state) => state.blog,
        getPhotos: (state) => state.photos,
    },
    actions: {
        addBlog(blog) {
            this.blog = blog
        },
        addPhotos(photos) {
            this.photos = photos
        },
    },
})
