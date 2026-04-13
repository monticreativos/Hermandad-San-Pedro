import { Head, router, usePage } from '@inertiajs/react';
import Swal from 'sweetalert2';
import { useState } from 'react';
import { useForm } from 'react-hook-form';
import { z } from 'zod';
import MainLayout from '../../Layouts/MainLayout';
import PageTransition from '../../Components/PageTransition';

const step1Schema = z.object({
    full_name: z.string().min(2),
    email: z.string().email(),
    phone: z.string().optional(),
    birth_date: z.string().optional(),
});

const fullSchema = step1Schema.extend({
    address: z.string().optional(),
    message: z.string().optional(),
});

export default function MemberApplicationIndex() {
    const { locale = 'es', translations = {} } = usePage().props;
    const t = (key, fallback) => translations[key] ?? fallback;
    const [step, setStep] = useState(0);

    const {
        register,
        handleSubmit,
        setError,
        clearErrors,
        formState: { errors },
        reset,
        getValues,
    } = useForm({
        defaultValues: {
            full_name: '',
            email: '',
            phone: '',
            birth_date: '',
            address: '',
            message: '',
        },
    });

    const applyZodErrors = (result) => {
        clearErrors();
        if (result.success) {
            return true;
        }
        result.error.issues.forEach((issue) => {
            const path = issue.path[0];
            if (path) {
                setError(path, { message: issue.message });
            }
        });
        return false;
    };

    const goNext = () => {
        const parsed = step1Schema.safeParse(getValues());
        if (applyZodErrors(parsed)) {
            setStep(1);
        }
    };

    const onFinalSubmit = () => {
        const parsed = fullSchema.safeParse(getValues());
        if (!applyZodErrors(parsed)) {
            return;
        }

        router.post(route('member_application.store', { locale }), parsed.data, {
            preserveScroll: true,
            onSuccess: () => {
                Swal.fire({
                    icon: 'success',
                    title: t('member.successTitle', 'Solicitud enviada'),
                    text: t(
                        'member.successText',
                        'Gracias. Hemos registrado su solicitud.',
                    ),
                    confirmButtonColor: '#4b1f6f',
                });
                reset();
                setStep(0);
            },
            onError: (errs) => {
                Object.entries(errs).forEach(([key, messages]) => {
                    const msg = Array.isArray(messages) ? messages[0] : messages;
                    setError(key, { message: msg });
                });
            },
        });
    };

    return (
        <MainLayout>
            <Head title={t('member.title', locale === 'es' ? 'Hazte hermano' : 'Become a member')} />

            <PageTransition>
                <section className="mx-auto max-w-[90rem] px-4 py-10 sm:px-6 lg:px-8">
                    <p className="max-w-2xl text-zinc-600">
                        {t('member.subtitle', '')}
                    </p>

                    <div className="mt-6 flex gap-2 text-sm font-semibold text-[#4b1f6f]">
                        <span className={step === 0 ? 'opacity-100' : 'opacity-40'}>
                            1. {t('member.step1', 'Datos personales')}
                        </span>
                        <span aria-hidden="true">/</span>
                        <span className={step === 1 ? 'opacity-100' : 'opacity-40'}>
                            2. {t('member.step2', 'Direccion y mensaje')}
                        </span>
                    </div>

                    <form
                        onSubmit={handleSubmit(step === 0 ? goNext : onFinalSubmit)}
                        className="mt-8 max-w-2xl space-y-4 rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm"
                    >
                        {step === 0 ? (
                            <>
                                <div>
                                    <label className="mb-1 block text-sm font-semibold text-zinc-700">
                                        {t('member.fullName', 'Nombre y apellidos')}
                                    </label>
                                    <input
                                        {...register('full_name')}
                                        className="w-full rounded-xl border border-zinc-300 px-3 py-2.5 outline-none focus:border-[#4b1f6f]"
                                    />
                                    {errors.full_name ? (
                                        <p className="mt-1 text-xs text-rose-600">{errors.full_name.message}</p>
                                    ) : null}
                                </div>
                                <div>
                                    <label className="mb-1 block text-sm font-semibold text-zinc-700">
                                        {t('member.email', 'Email')}
                                    </label>
                                    <input
                                        type="email"
                                        {...register('email')}
                                        className="w-full rounded-xl border border-zinc-300 px-3 py-2.5 outline-none focus:border-[#4b1f6f]"
                                    />
                                    {errors.email ? (
                                        <p className="mt-1 text-xs text-rose-600">{errors.email.message}</p>
                                    ) : null}
                                </div>
                                <div>
                                    <label className="mb-1 block text-sm font-semibold text-zinc-700">
                                        {t('member.phone', 'Telefono')}
                                    </label>
                                    <input
                                        {...register('phone')}
                                        className="w-full rounded-xl border border-zinc-300 px-3 py-2.5 outline-none focus:border-[#4b1f6f]"
                                    />
                                </div>
                                <div>
                                    <label className="mb-1 block text-sm font-semibold text-zinc-700">
                                        {t('member.birthDate', 'Fecha de nacimiento')}
                                    </label>
                                    <input
                                        type="date"
                                        {...register('birth_date')}
                                        className="w-full rounded-xl border border-zinc-300 px-3 py-2.5 outline-none focus:border-[#4b1f6f]"
                                    />
                                    {errors.birth_date ? (
                                        <p className="mt-1 text-xs text-rose-600">{errors.birth_date.message}</p>
                                    ) : null}
                                </div>
                            </>
                        ) : (
                            <>
                                <div>
                                    <label className="mb-1 block text-sm font-semibold text-zinc-700">
                                        {t('member.address', 'Direccion')}
                                    </label>
                                    <textarea
                                        rows={3}
                                        {...register('address')}
                                        className="w-full rounded-xl border border-zinc-300 px-3 py-2.5 outline-none focus:border-[#4b1f6f]"
                                    />
                                </div>
                                <div>
                                    <label className="mb-1 block text-sm font-semibold text-zinc-700">
                                        {t('member.message', 'Mensaje')}
                                    </label>
                                    <textarea
                                        rows={5}
                                        {...register('message')}
                                        className="w-full rounded-xl border border-zinc-300 px-3 py-2.5 outline-none focus:border-[#4b1f6f]"
                                    />
                                </div>
                            </>
                        )}

                        <div className="flex flex-wrap gap-3 pt-2">
                            {step === 1 ? (
                                <button
                                    type="button"
                                    onClick={() => setStep(0)}
                                    className="rounded-xl border border-zinc-300 px-5 py-2.5 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-50"
                                >
                                    {t('member.back', 'Volver')}
                                </button>
                            ) : null}
                            <button
                                type="submit"
                                className="rounded-xl bg-[#4b1f6f] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#3b1758]"
                            >
                                {step === 0
                                    ? t('member.next', 'Continuar')
                                    : t('member.submit', 'Enviar solicitud')}
                            </button>
                        </div>
                    </form>
                </section>
            </PageTransition>
        </MainLayout>
    );
}
