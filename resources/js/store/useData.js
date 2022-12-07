import {defineStore} from "pinia";
export const useData = defineStore('useData', {
    state: () => ({
        data: '',
    }),
    getters: {
        getData: (state) => state.data,
    },
    actions: {
        addData(data) {
            this.data = data
        },
    },
})
