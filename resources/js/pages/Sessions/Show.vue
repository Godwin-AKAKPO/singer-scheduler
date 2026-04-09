<template>
    <AppLayout>
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                {{ getNomMois(session.mois) }} {{ session.annee }}
            </h1>
            <div class="flex gap-3">
                <Link :href="route('sessions.index')"
                      class="text-sm text-gray-600 hover:underline">
                    ← Retour
                </Link>
                <button @click="generer"
                        class="bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-800">
                    {{ session.programmation ? '🔄 Regénérer' : '⚡ Générer la programmation' }}
                </button>
                <a :href="route('programmation.pdf', session.id)"
   target="_blank"
   class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700">
    📄 Exporter PDF
</a>
            </div>
        </div>

        <!-- Pas encore générée -->
        <div v-if="!session.programmation"
             class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 text-center text-yellow-700">
            La programmation n'a pas encore été générée. Cliquez sur "Générer" pour démarrer.
        </div>

        <!-- Tableau de programmation -->
        <div v-else class="space-y-6">
            <div v-for="dimanche in dimanches" :key="dimanche" class="space-y-3">
                <h2 class="text-base font-semibold text-gray-700">
                    📅 {{ formatDate(dimanche) }}
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div v-for="culte in ['C1', 'C2']" :key="culte"
                         class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="bg-blue-700 text-white px-4 py-2 text-sm font-medium">
                            {{ culte === 'C1' ? '1er Culte' : '2ème Culte' }}
                        </div>
                        <div class="p-4 space-y-2 text-sm">
                            <template v-if="getCulte(dimanche, culte)">
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Lead</span>
                                    <span class="font-medium">{{ getCulte(dimanche, culte).lead[0] ?? '—' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Chœur P1</span>
                                    <span class="font-medium">{{ getCulte(dimanche, culte).choeur.p1.join(', ') || '—' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Chœur P2</span>
                                    <span class="font-medium">{{ getCulte(dimanche, culte).choeur.p2[0] ?? '—' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Chœur P3</span>
                                    <span class="font-medium">{{ getCulte(dimanche, culte).choeur.p3[0] ?? '—' }}</span>
                                </div>
                                <div class="border-t border-gray-100 pt-2 flex justify-between">
                                    <span class="text-gray-500">Piano 1</span>
                                    <span class="font-medium">{{ getCulte(dimanche, culte).piano1[0] ?? '—' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Piano 2</span>
                                    <span class="font-medium">{{ getCulte(dimanche, culte).piano2[0] ?? '—' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Solo</span>
                                    <span class="font-medium">{{ getCulte(dimanche, culte).solo[0] ?? '—' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Basse</span>
                                    <span class="font-medium">{{ getCulte(dimanche, culte).basse[0] ?? '—' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Batterie</span>
                                    <span class="font-medium">{{ getCulte(dimanche, culte).batterie[0] ?? '—' }}</span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>


<script setup>
import AppLayout from '@/pages/AppLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    session: Object,
});

const moisNoms = [
    'Janvier','Février','Mars','Avril','Mai','Juin',
    'Juillet','Août','Septembre','Octobre','Novembre','Décembre'
];

const getNomMois = (m) => moisNoms[m - 1];

const dimanches = computed(() => {
    const result = [];
    const date = new Date(props.session.annee, props.session.mois - 1, 1);
    while (date.getMonth() === props.session.mois - 1) {
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
    const date = new Date(y, m - 1, d);
    return date.toLocaleDateString('fr-FR', {
        weekday: 'long',
        day: 'numeric',
        month: 'long'
    });
};

const getCulte = (dimanche, culte) => {
    return props.session.programmation?.find(
        p => p.date === dimanche && p.culte === culte
    );
};

const generer = () => {
    router.post(route('programmation.generer', props.session.id));
};
</script>