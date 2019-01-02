<template>
  <input type="text" minlength="5" maxlength="8" pattern="[a-zA-Z0-9]{2,4} ?\d[a-zA-Z]{2}" v-model="value">
</template>

<script>
import { normalise } from '../utils/postcodes';

export default {
    data() {
        return {
            value: normalise(this.$attrs.value || ''),
        }
    },
    watch: {
        value(value) {
            this.value = normalise(value);
        }
    },
};
</script>
