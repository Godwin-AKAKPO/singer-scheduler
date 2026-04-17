<template>
    <div class="relative">
        <select
            :value="valeur"
            @change="e => emit('change', e.target.value)"
            @focus="charger"
            class="w-full text-sm font-medium text-gray-800 border border-emerald-300 rounded-md px-2 py-1 focus:outline-none focus:ring-2 focus:ring-emerald-400 bg-white appearance-none pr-6"
        >
            <option value="">— choisir —</option>
            <!-- Valeur actuelle toujours présente même si plus dispo -->
            <option v-if="valeur && !membres.includes(valeur)" :value="valeur">
                {{ valeur }} (actuel)
            </option>
            <option v-for="nom in membres" :key="nom" :value="nom">{{ nom }}</option>
        </select>

        <!-- Icône chevron -->
        <svg class="pointer-events-none absolute right-1.5 top-1/2 -translate-y-1/2 h-3 w-3 text-gray-400"
            fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>

        <!-- Loading -->
        <div v-if="chargement" class="absolute inset-0 flex items-center justify-center bg-white/70 rounded-md">
            <svg class="h-3.5 w-3.5 text-emerald-500 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
            </svg>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const props = defineProps({
    sessionId: { type: Number, required: true },
    dimanche:  { type: String, required: true },
    culte:     { type: String, required: true },
    role:      { type: String, required: true },
    valeur:    { type: String, default: '' },
});

const emit = defineEmits(['change']);

const membres    = ref([]);
const chargement = ref(false);
const charge     = ref(false);

const charger = async () => {
    if (charge.value) return; // charger une seule fois
    chargement.value = true;
    try {
        const { data } = await axios.get(
            `/sessions/${props.sessionId}/membres-disponibles`,
            { params: { dimanche: props.dimanche, culte: props.culte, role: props.role } }
        );
        membres.value = data;
        charge.value  = true;
    } catch (e) {
        console.error('Erreur chargement membres', e);
    } finally {
        chargement.value = false;
    }
};
</script>