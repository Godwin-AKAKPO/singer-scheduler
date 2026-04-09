<template>
    <AppLayout>
        <div class="max-w-2xl mx-auto">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Nouvelle session</h1>

            <form @submit.prevent="soumettre"
                  class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-6">

                <!-- Mois et année -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mois</label>
                        <select v-model.number="form.mois"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option v-for="(nom, index) in mois" :key="index" :value="index + 1">
                                {{ nom }}
                            </option>
                        </select>
                        <p v-if="form.errors.mois" class="text-red-500 text-xs mt-1">{{ form.errors.mois }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Année</label>
                        <input v-model.number="form.annee" type="number" min="2024"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        <p v-if="form.errors.annee" class="text-red-500 text-xs mt-1">{{ form.errors.annee }}</p>
                    </div>
                </div>

                <!-- Dimanches calculés -->
                <div class="bg-blue-50 rounded-lg px-4 py-3 text-sm text-blue-700">
                    📅 Ce mois contient <strong>{{ dimanches.length }} dimanche(s)</strong> :
                    {{ dimanches.join(', ') }}
                </div>

                <!-- Absences -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-medium text-gray-700">Absences déclarées</label>
                        <button type="button" @click="ajouterAbsence"
                                class="text-xs text-blue-600 hover:underline">
                            + Ajouter
                        </button>
                    </div>

                    <div v-if="form.absences.length === 0" class="text-sm text-gray-400 italic">
                        Aucune absence déclarée — tous les membres sont disponibles.
                    </div>

                    <div v-for="(absence, index) in form.absences" :key="index"
                         class="flex gap-3 items-start mb-3 p-3 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <select v-model="absence.nom"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mb-2">
                                <option value="">-- Choisir un membre --</option>
                                <option v-for="membre in membres" :key="membre.id" :value="membre.nom">
                                    {{ membre.nom }}
                                </option>
                            </select>
                            <div class="flex flex-wrap gap-2">
                                <label v-for="dimanche in dimanches" :key="dimanche"
                                       class="flex items-center gap-1 text-xs">
                                    <input type="checkbox" :value="dimanche" v-model="absence.dates" />
                                    {{ formatDate(dimanche) }}
                                </label>
                            </div>
                        </div>
                        <button type="button" @click="retirerAbsence(index)"
                                class="text-red-400 hover:text-red-600 text-xs mt-1">
                            ✕
                        </button>
                    </div>
                </div>

                <!-- Boutons -->
                <div class="flex justify-end gap-3 pt-2">
                    <Link :href="route('sessions.index')"
                          class="px-4 py-2 text-sm text-gray-600 hover:underline">
                        Annuler
                    </Link>
                    <button type="submit"
                            :disabled="form.processing"
                            class="bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-blue-800 disabled:opacity-50">
                        Créer la session
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/pages/AppLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    membres: Array,
    session: Object,
});

const mois = [
    'Janvier','Février','Mars','Avril','Mai','Juin',
    'Juillet','Août','Septembre','Octobre','Novembre','Décembre'
];

const form = useForm({
    annee:    new Date().getFullYear(),
    mois:     new Date().getMonth() + 1,
    absences: [],
});

const dimanches = computed(() => {
    const result = [];
    const date = new Date(form.annee, form.mois - 1, 1);
    while (date.getMonth() === form.mois - 1) {
        if (date.getDay() === 0) {
            result.push(date.toISOString().split('T')[0]);
        }
        date.setDate(date.getDate() + 1);
    }
    return result;
});

const formatDate = (dateStr) => {
    const d = new Date(dateStr);
    return d.toLocaleDateString('fr-FR', { day: 'numeric', month: 'short' });
};

const ajouterAbsence = () => {
    form.absences.push({ nom: '', dates: [] });
};

const retirerAbsence = (index) => {
    form.absences.splice(index, 1);
};

const soumettre = () => {
    form.post(route('sessions.store'));
};
</script>