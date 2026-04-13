import { Head, usePage } from '@inertiajs/react';
import Swal from 'sweetalert2';
import { useForm } from 'react-hook-form';
import { z } from 'zod';
import { zodResolver } from '@hookform/resolvers/zod';
import MainLayout from '../../Layouts/MainLayout';
import PageTransition from '../../Components/PageTransition';

export default function ContactIndex() {
    const { locale = 'es', translations = {} } = usePage().props;
    const t = (key, fallback) => translations[key] ?? fallback;

    const schema = z.object({
        name: z.string().min(2),
        email: z.string().email(),
        subject: z.string().min(3),
        message: z.string().min(10),
    });

    const {
        register,
        handleSubmit,
        formState: { errors, isSubmitting },
        reset,
    } = useForm({
        resolver: zodResolver(schema),
        defaultValues: {
            name: '',
            email: '',
            subject: '',
            message: '',
        },
    });

    const onSubmit = async () => {
        await new Promise((resolve) => setTimeout(resolve, 700));

        await Swal.fire({
            icon: 'success',
            title: t('contact.successTitle', 'Mensaje enviado'),
            text: t(
                'contact.successText',
                'Su mensaje ha sido enviado, que nuestros Titulares le guien.',
            ),
            confirmButtonColor: '#4b1f6f',
        });

        reset();
    };

    return (
        <MainLayout>
            <Head title={t('contact.title', locale === 'es' ? 'Contacto' : 'Contact')} />

            <PageTransition>
                <section className="mx-auto max-w-[90rem] px-4 py-10 sm:px-6 lg:px-8">
                    <p className="text-zinc-600">
                        {t('contact.subtitle', '')}
                    </p>

                    <form
                        onSubmit={handleSubmit(onSubmit)}
                        className="mt-8 max-w-2xl space-y-4 rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm"
                    >
                        <div>
                            <label className="mb-1 block text-sm font-semibold text-zinc-700">
                                {t('contact.name', 'Nombre')}
                            </label>
                            <input
                                {...register('name')}
                                className="w-full rounded-xl border border-zinc-300 px-3 py-2.5 outline-none focus:border-[#4b1f6f]"
                            />
                            {errors.name ? (
                                <p className="mt-1 text-xs text-rose-600">
                                    {locale === 'es'
                                        ? 'Nombre obligatorio'
                                        : 'Name is required'}
                                </p>
                            ) : null}
                        </div>

                        <div>
                            <label className="mb-1 block text-sm font-semibold text-zinc-700">
                                {t('contact.email', 'Email')}
                            </label>
                            <input
                                type="email"
                                {...register('email')}
                                className="w-full rounded-xl border border-zinc-300 px-3 py-2.5 outline-none focus:border-[#4b1f6f]"
                            />
                            {errors.email ? (
                                <p className="mt-1 text-xs text-rose-600">
                                    {locale === 'es'
                                        ? 'Email invalido'
                                        : 'Invalid email'}
                                </p>
                            ) : null}
                        </div>

                        <div>
                            <label className="mb-1 block text-sm font-semibold text-zinc-700">
                                {t('contact.subject', 'Asunto')}
                            </label>
                            <input
                                {...register('subject')}
                                className="w-full rounded-xl border border-zinc-300 px-3 py-2.5 outline-none focus:border-[#4b1f6f]"
                            />
                            {errors.subject ? (
                                <p className="mt-1 text-xs text-rose-600">
                                    {locale === 'es'
                                        ? 'Asunto obligatorio'
                                        : 'Subject is required'}
                                </p>
                            ) : null}
                        </div>

                        <div>
                            <label className="mb-1 block text-sm font-semibold text-zinc-700">
                                {t('contact.message', 'Mensaje')}
                            </label>
                            <textarea
                                rows={5}
                                {...register('message')}
                                className="w-full rounded-xl border border-zinc-300 px-3 py-2.5 outline-none focus:border-[#4b1f6f]"
                            />
                            {errors.message ? (
                                <p className="mt-1 text-xs text-rose-600">
                                    {locale === 'es'
                                        ? 'Mensaje obligatorio'
                                        : 'Message is required'}
                                </p>
                            ) : null}
                        </div>

                        <button
                            type="submit"
                            disabled={isSubmitting}
                            className="rounded-xl bg-[#4b1f6f] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#3b1758] disabled:opacity-60"
                        >
                            {t('contact.send', 'Enviar mensaje')}
                        </button>
                    </form>
                </section>
            </PageTransition>
        </MainLayout>
    );
}
