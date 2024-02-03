function randomId() {
    const uint32 = window.crypto.getRandomValues(new Uint32Array(1))[0];
    return uint32.toString(16);
}

const toast = Swal.mixin({
    toast: true,
    position: "bottom",
    showConfirmButton: false,
    showCloseButton: true,
    timer: 3000,
    timerProgressBar: true,
})

window.toast = toast

window.tooltip = (el, title) => new bootstrap.Tooltip(el, { title })

document.addEventListener("alpine:init", () => {


    Alpine.data("familyRelationship", (old, errors, defaultValues = null) => ({
        old: JSON.parse(old),
        errors: JSON.parse(errors),
        defaultValues: JSON.parse(defaultValues),
        deleteIDs: [],
        elements: [
            {
                id: randomId(),
                old: {
                    id: null,
                    hubunganKeluarga: "",
                    namaKeluarga: "",
                    tanggalLahirKeluarga: ""
                },
                errors: {
                    hubunganKeluarga: "",
                    namaKeluarga: "",
                    tanggalLahirKeluarga: ""
                }
            }
        ],
        init() {
            this._handleDefault();
            this._handleOld();
        },

        addOrRemove(id, deleteID = null) {
            console.log(deleteID)
            if (id === this.elements[0].id) {
                this.elements.push(this._newElement())
            } else {
                this.elements = this.elements.filter(element => element.id !== id)

                if (deleteID) {
                    this.deleteIDs.push(deleteID)
                }
            }
        },

        getOld(id, name) {
            if (!this.old && !this.defaultValues) return "";

            const element = this.elements.find(element => element.id === id)

            if (!element) return "";

            return element.old[name]
        },

        getError(id, name) {
            if (!this.old) return "";

            const element = this.elements.find(element => element.id === id)

            if (!element) return "";

            return element.errors[name];
        },

        hasError(id, name) {
            if (!this.old) return false;

            const element = this.elements.find(element => element.id === id)

            if (!element) return false;

            return !!element.errors[name];
        },

        _handleOld() {
            if (this.old) {

                if (this.old.delete_id) {
                    this.deleteIDs = this.old.delete_id
                }

                this._populateOld();
                this._populateErrors();

                const familiesCount = Number(this.old.jumlah_keluarga)

                if (familiesCount > 1) {
                    for (let index = 1; index < familiesCount; index++) {
                        if (!this.defaultValues || index >= this.defaultValues.length) {
                            this.elements.push(this._newElement())
                        }

                        this._populateOld(index)
                        this._populateErrors(index)
                    }
                }
            }
        },

        _handleDefault() {
            if (this.defaultValues) {
                this._populateDefaults()

                const familiesCount = this.defaultValues.length

                if (familiesCount > 1) {
                    for (let index = 1; index < familiesCount; index++) {
                        if (this.old && familiesCount > Number(this.old.jumlah_keluarga)) {
                            break;
                        }

                        this.elements.push(this._newElement())

                        this._populateDefaults(index)
                    }
                }
            }
        },

        _populateDefaults(index = 0) {
            this.elements[index].old = {
                id: this._getDefault("id", index),
                hubunganKeluarga: this._getDefault("hubungan", index),
                namaKeluarga: this._getDefault("nama", index),
                tanggalLahirKeluarga: this._getDefault("tanggal_lahir", index),
            }
        },

        _populateOld(index = 0) {
            this.elements[index].old = {
                id: this._getDefault("id", index),
                hubunganKeluarga: this._getOld("hubungan_keluarga", index),
                namaKeluarga: this._getOld("nama_keluarga", index),
                tanggalLahirKeluarga: this._getOld("tanggal_lahir_keluarga", index),
            }
        },

        _populateErrors(index = 0) {
            this.elements[index].errors = {
                hubunganKeluarga: this.errors?.[`hubungan_keluarga.${index}`] || "",
                namaKeluarga: this.errors?.[`nama_keluarga.${index}`] || "",
                tanggalLahirKeluarga: this.errors?.[`tanggal_lahir_keluarga.${index}`] || "",
            }
        },

        _getOld(name, index) {
            return this.old ? this.old[name][index] : "";
        },

        _getDefault(name, index) {
            return this.defaultValues?.[index]?.[name] || "";
        },

        _newElement() {
            return {
                id: randomId(),
                old: {
                    id: null,
                    hubunganKeluarga: "",
                    namaKeluarga: "",
                    tanggalLahirKeluarga: ""
                },
                errors: {
                    hubunganKeluarga: "",
                    namaKeluarga: "",
                    tanggalLahirKeluarga: ""
                }
            }
        },

    }))
})