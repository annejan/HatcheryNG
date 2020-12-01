<template>
    <jet-form-section>
        <template #title>
            Projects
        </template>

        <template #description>
            The team's projects are linked here.
        </template>

        <template #form>
            <div class="col-span-6" v-for="(project, i) in team.projects"
                 :key="project.key">
                <jet-label :value="project.name" />

                <div class="flex items-center mt-2">

                    <div class="ml-4 leading-tight">
                        <div>{{ project.description }}</div>
                    </div>
                </div>
            </div>
        </template>
    </jet-form-section>
</template>

<script>
    import JetActionMessage from '@/Jetstream/ActionMessage'
    import JetButton from '@/Jetstream/Button'
    import JetFormSection from '@/Jetstream/FormSection'
    import JetInput from '@/Jetstream/Input'
    import JetInputError from '@/Jetstream/InputError'
    import JetLabel from '@/Jetstream/Label'

    export default {
        components: {
            JetActionMessage,
            JetButton,
            JetFormSection,
            JetInput,
            JetInputError,
            JetLabel,
        },

        props: ['team', 'permissions'],

        data() {
            return {
                form: this.$inertia.form({
                    team: this.team,
                })
            }
        },

    }
</script>
