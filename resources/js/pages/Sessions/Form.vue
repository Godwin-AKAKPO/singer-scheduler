<template>
    <AppLayout>
        <div class="max-w-2xl mx-auto space-y-6">

            <!-- Header -->
            <div class="flex items-center gap-4">
                <Link :href="route('sessions.index')" class="flex items-center justify-center w-8 h-8 rounded-lg bg-white border border-gray-200 text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </Link>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Nouvelle session</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Déclarez les absences avant de générer la programmation</p>
                </div>
            </div>

            <form @submit.prevent="soumettre" class="space-y-6">

                <!-- Mois / Année -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                    <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4">Période</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Mois</label>
                            <select v-model.number="form.mois"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option v-for="(nom, index) in moisNoms" :key="index" :value="index + 1">{{ nom }}</option>
                            </select>
                            <p v-if="form.errors.mois" class="text-red-500 text-xs mt-1">{{ form.errors.mois }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Année</label>
                            <input v-model.number="form.annee" type="number" min="2024"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                            <p v-if="form.errors.annee" class="text-red-500 text-xs mt-1">{{ form.errors.annee }}</p>
                        </div>
                    </div>

                    <!-- Dimanches calculés -->
                    <div class="mt-4 flex items-center gap-2 p-3 bg-blue-50 border border-blue-200 rounded-lg text-sm text-blue-700">
                        <svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span><strong>{{ dimanches.length }} dimanche(s)</strong> : {{ dimanches.map(formatDate).join(' — ') }}</span>
                    </div>
                </div>

                <!-- Absences -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Absences déclarées</h2>
                        <button type="button" @click="ajouterAbsence"
                            class="inline-flex items-center gap-1.5 text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Ajouter
                        </button>
                    </div>

                    <div v-if="form.absences.length === 0" class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg text-sm text-gray-500">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Tous les membres sont disponibles ce mois-ci.
                    </div>

                    <div class="space-y-3">
                        <div v-for="(absence, index) in form.absences" :key="index"
                            class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex items-start gap-3">
                                <div class="flex-1 space-y-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Membre</label>
                                        <select v-model="absence.nom"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="">-- Choisir un membre --</option>
                                            <option v-for="membre in membres" :key="membre.id" :value="membre.nom">{{ membre.nom }}</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-2">Dimanches concernés</label>
                                        <div class="flex flex-wrap gap-2">
                                            <label v-for="dimanche in dimanches" :key="dimanche"
                                                class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border text-xs font-medium cursor-pointer transition-colors"
                                                :class="absence.dates.includes(dimanche)
                                                    ? 'bg-red-50 border-red-300 text-red-700'
                                                    : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50'"
                                            >
                                                <input type="checkbox" :value="dimanche" v-model="absence.dates" class="rounded text-red-500" />
                                                {{ formatDate(dimanche) }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" @click="retirerAbsence(index)"
                                    class="flex-shrink-0 flex items-center justify-center w-7 h-7 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 transition-colors">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Boutons -->
                <div class="flex items-center justify-end gap-3">
                    <Link :href="route('sessions.index')"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                        Annuler
                    </Link>
                    <button type="submit" :disabled="form.processing"
                        class="inline-flex items-center gap-2 px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm disabled:opacity-50">
                        <svg v-if="form.processing" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                        </svg>
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

const props = defineProps({ membres: Array, session: Object });

const moisNoms = ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];

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
            const y = date.getFullYear();
            const m = String(date.getMonth() + 1).padStart(2, '0');
            const d = String(date.getDate()).padStart(2, '0');
            result.push(`${y}-${m}-${d}`);
        }
        date.setDate(date.getDate() + 1);
    }
    return result;
});

const formatDate = (dateStr) => {
    const [y, m, d] = dateStr.split('-').map(Number);
    return new Date(y, m - 1, d).toLocaleDateString('fr-FR', { day: 'numeric', month: 'short' });
};

const ajouterAbsence = () => form.absences.push({ nom: '', dates: [] });
const retirerAbsence = (index) => form.absences.splice(index, 1);
const soumettre = () => form.post(route('sessions.store'));
</script>