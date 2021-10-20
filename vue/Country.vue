<template>
    <a-spin :spinning="loading">
        <div
            v-if="mode"
            @click="
                (e) => {
                    e.preventDefault();
                    selectOpen = !selectOpen;
                }
            "
        >
            <a-select ref="selectInput" v-model:value="value" :open="selectOpen" @blur="blurEvent" :placeholder="placeholder" mode="multiple">
                <template #dropdownRender="{ menuNode: menu }">
                    <div @mousedown="(e) => e.preventDefault()" @click="(e) => e.target.focus()"><a-input v-model:value="search" @blur="blurEvent" class="inputSearch" mode="multiple" placeholder="Please search a country" /></div>
                    <v-nodes :vnodes="menu" />
                </template>
                <a-select-option :label="v.name" :value="v.id" v-for="(v, k) in list" :key="k">
                    {{ v.name }}
                </a-select-option>
            </a-select>
        </div>

        <div
            v-else
            @click="
                (e) => {
                    e.preventDefault();
                    selectOpen = !selectOpen;
                }
            "
        >
            <a-select ref="selectInput" v-model:value="value" :open="selectOpen" @select="selectOpen = false" @blur="blurEvent" :placeholder="placeholder">
                <template #dropdownRender="{ menuNode: menu }">
                    <div @mousedown="(e) => e.preventDefault()" @click="(e) => e.target.focus()"><a-input v-model:value="search" @blur="blurEvent" class="inputSearch" placeholder="Please search a country" /></div>
                    <v-nodes :vnodes="menu" />
                </template>
                <a-select-option :label="v.name" :value="v.id" v-for="(v, k) in list" :key="k">
                    {{ v.name }}
                </a-select-option>
            </a-select>
        </div>
    </a-spin>
</template>
<script>
    import { getCountry } from '/@/api/tool';

    export default {
        components: {
            VNodes: (_, { attrs }) => {
                return attrs.vnodes;
            },
        },
        props: {
            mode: {
                type: Boolean,
                default: false,
            },
            country: {
                type: Number || Array,
                default: 0,
            },
            placeholder: {
                type: String,
                default: 'Select or Search a Country',
            },
        },
        emits: ['update:country'],
        data() {
            return {
                options: [],
                value: undefined,
                loading: true,
                search: '',
                selectOpen: false,
                searchTips: '请输入关键词',
            };
        },
        computed: {
            list() {
                let search = this.search;
                if (search) {
                    return this.options.filter((option) => {
                        return option.name.indexOf(search) >= 0;
                    });
                }
                return this.options;
            },
        },
        watch: {
            country() {
                this.initVal();
            },
            selectOpen() {
                this.search = undefined;
            },
        },
        mounted() {
            this.initCountry();
            this.initVal();
        },
        methods: {
            filterOption(input, option) {
                return option.children[0].children.indexOf(input) >= 0;
            },
            handleChange(countryId) {
                this.$emit('update:country', countryId);
            },
            setDefaultValue() {
                if (!this.options) {
                    return;
                }
                this.$nextTick(() => {
                    if (!this.mode) {
                        this.value = this.options[0].id;
                    } else {
                        this.value = [];
                    }
                });
                this.handleChange(this.value);
            },
            async initCountry() {
                const { data } = await getCountry();
                this.options = data;
                this.loading = false;
            },
            initVal() {
                if (Array.isArray(this.country)) {
                    this.value = this.country.map((c) => {
                        return parseInt(c);
                    });
                } else if (this.country) {
                    this.value = parseInt(this.country);
                }
            },
            blurEvent() {
                let f = 'inputSearch';
                let classes = document.activeElement.getAttribute('class');
                console.log(classes)
                if (!classes) {
                    this.selectOpen = false;
                    return;
                }
                if (classes.indexOf(f) <= 0) {
                    this.selectOpen = false;
                }
            },
        },
    };
</script>

<style lang="less" scoped>
    .country {
        min-width: 200px;
        cursor: pointer;
    }
</style>
