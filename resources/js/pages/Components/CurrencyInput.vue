<template>
  <div>
    <input ref="inputRef" type="text" class="block w-full rounded-md p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
  </div>
</template>

<script>
import { useCurrencyInput } from "vue-currency-input";
import {watch} from "vue";

export default {
  name: "CurrencyInput",
  props: {
    modelValue: Number,
    options: Object,
  },

  setup(props, {emit}) {
    const options = {
      "currency": "EUR",
      "currencyDisplay": "symbol",
      "hideCurrencySymbolOnFocus": false,
      "hideGroupingSeparatorOnFocus": false,
      "hideNegligibleDecimalDigitsOnFocus": false,
      "autoDecimalDigits": true,
      "valueScaling": "precision",
      "useGrouping": true,
      "accountingSign": false
    }
    const { inputRef, setValue} = useCurrencyInput(options);

    // Watch the input changed in parent and update in the input.
    watch(
        () => props.modelValue,
        (value) => {
          setValue(value)
        }
    )

    return { inputRef }
  }

}
</script>