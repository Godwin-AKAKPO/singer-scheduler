<template>
    <AppLayout>
        <div class="max-w-2xl mx-auto">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">
                {{ membre ? 'Modifier un membre' : 'Ajouter un membre' }}
            </h1>

            <form @submit.prevent="soumettre" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-6">

                <!-- Nom -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
                    <input v-model="form.nom" type="text"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Ex: Benjamin" />
                    <p v-if="form.errors.nom" class="text-red-500 text-xs mt-1">{{ form.errors.nom }}</p>
                </div>

                <!-- Cultes autorisés -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cultes autorisés</label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" v-model="form.cultes_autorises" value="C1"
                                   class="rounded" />
                            Culte 1
                        </label>
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" v-model="form.cultes_autorises" value="C2"
                                   class="rounded" />
                            Culte 2
                        </label>
                    </div>
                    <p v-if="form.errors.cultes_autorises" class="text-red-500 text-xs mt-1">{{ form.errors.cultes_autorises }}</p>
                </div>

                <!-- Rôles -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rôles éligibles</label>
                    <div class="grid grid-cols-2 gap-2">
                        <label v-for="role in roles" :key="role.key"
                               class="flex items-center gap-2 text-sm bg-gray-50 px-3 py-2 rounded-lg">
                            <input type="checkbox" v-model="form[role.key]" class="rounded" />
                            {{ role.label }}
                        </label>
                    </div>
                </div>

                <!-- Score -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Score de préférence :
                        <span class="text-blue-700 font-bold">{{ form.score }}/10</span>
                    </label>
                    <input type="range" v-model.number="form.score" min="1" max="10"
                           class="w-full accent-blue-700" />
                    <div class="flex justify-between text-xs text-gray-400 mt-1">
                        <span>1 — débutant</span>
                        <span>10 — expert</span>
                    </div>
                </div>

                <!-- Boutons -->
                <div class="flex justify-end gap-3 pt-2">
                    <Link :href="route('membres.index')"
                          class="px-4 py-2 text-sm text-gray-600 hover:underline">
                        Annuler
                    </Link>
                    <button type="submit"
                            :disabled="form.processing"
                            class="bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-blue-800 disabled:opacity-50">
                        {{ membre ? 'Mettre à jour' : 'Enregistrer' }}
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/pages/AppLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    membre: Object,
});

const roles = [
    { key: 'lead',      label: 'Lead'      },
    { key: 'choeur_p1', label: 'Chœur P1'  },
    { key: 'choeur_p2', label: 'Chœur P2'  },
    { key: 'choeur_p3', label: 'Chœur P3'  },
    { key: 'piano1',    label: 'Piano 1'   },
    { key: 'piano2',    label: 'Piano 2'   },
    { key: 'solo',      label: 'Solo'      },
    { key: 'basse',     label: 'Basse'     },
    { key: 'batterie',  label: 'Batterie'  },
];

const form = useForm({
    nom:              props.membre?.nom              ?? '',
    cultes_autorises: props.membre?.cultes_autorises ?? [],
    lead:             props.membre?.lead             ?? false,
    choeur_p1:        props.membre?.choeur_p1        ?? false,
    choeur_p2:        props.membre?.choeur_p2        ?? false,
    choeur_p3:        props.membre?.choeur_p3        ?? false,
    piano1:           props.membre?.piano1           ?? false,
    piano2:           props.membre?.piano2           ?? false,
    solo:             props.membre?.solo             ?? false,
    basse:            props.membre?.basse            ?? false,
    batterie:         props.membre?.batterie         ?? false,
    score:            props.membre?.score            ?? 5,
});

const soumettre = () => {
    if (props.membre) {
        form.put(route('membres.update', props.membre.id));
    } else {
        form.post(route('membres.store'));
    }
};
</script>