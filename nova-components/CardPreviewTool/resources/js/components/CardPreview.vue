<template>
    <div class="el-card" :style="cardStyles" :class="cardClasses">
        <div class="el-card__inner">
            <img v-if="card.header_image_url" :src="card.header_image_url" class="el-card__header-image">
            <p v-if="card.subtitle" class="el-card__subtitle">{{ card.subtitle }}</p>
            <h1 v-if="card.title" class="el-card__title" v-html="formattedTitle()"></h1>
            <p v-if="card.description" class="el-card__description" v-html="formattedDescription()"></p>
            <span v-if="card.button_text" class="el-card__button">{{ card.button_text }}</span>
        </div>
    </div>
</template>

<script>
export default {
    props: ['card', 'width'],

    methods: {
        formattedDescription() {
            return this.card.description.replace(/\n/g, '<br>');
        },

        formattedTitle() {
            return this.card.title.replace(/\n/g, '<br>');
        }
    },

    computed: {
        cardStyles() {
            return {
               'backgroundColor': this.card.background_color ? '#' + this.card.background_color : 'transparent', 
               'backgroundImage': this.card.image_url ? `url('${this.card.image_url}')` : '',
               'width': this.width + 'px',
            }
        },

        cardClasses() {
            return {
               'el-card--text-color-dark': this.card.text_style === "Dark",
               'el-card--text-color-light': this.card.text_style === "Light",

               'el-card--overlay-dark': this.card.overlay_style === "Dark",
               'el-card--overlay-light': this.card.overlay_style === "Light",

               'el-card--content-vertical-alignment-top': this.card.content_vertical_alignment === "Top",
               'el-card--content-vertical-alignment-center': this.card.content_vertical_alignment === "Center",
               'el-card--content-vertical-alignment-bottom': this.card.content_vertical_alignment === "Bottom",

               'el-card--content-horizontal-alignment-left': this.card.content_horizontal_alignment === "Left",
               'el-card--content-horizontal-alignment-center': this.card.content_horizontal_alignment === "Center",
               'el-card--content-horizontal-alignment-right': this.card.content_horizontal_alignment === "Right",
            }
        }
    }
}
</script>
