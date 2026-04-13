import { Clock, Mail, MapPin, Phone, User } from 'lucide-react';

function hasVisibleContent(c) {
    if (!c) return false;
    const fields = [c.person_name, c.role, c.schedule, c.location, c.phone, c.email];
    return fields.some((v) => typeof v === 'string' && v.trim() !== '');
}

export default function ObraSocialCharityContactCard({ contact, locale = 'es' }) {
    if (!hasVisibleContent(contact)) {
        return null;
    }

    const labels =
        locale === 'en'
            ? {
                  boxTitle: 'Charity office',
                  role: 'Role',
                  schedule: 'Hours',
                  location: 'Location',
                  phone: 'Phone',
                  email: 'Email',
              }
            : {
                  boxTitle: 'Atención de Caridad',
                  role: 'Cargo',
                  schedule: 'Horario',
                  location: 'Lugar',
                  phone: 'Teléfono',
                  email: 'Correo',
              };

    return (
        <div className="not-prose mb-10 rounded-2xl border border-[#4b1f6f]/20 bg-gradient-to-br from-[#4b1f6f]/5 to-white p-6 shadow-sm sm:p-8">
            <h2 className="text-lg font-bold text-[#4b1f6f] sm:text-xl">{labels.boxTitle}</h2>
            <dl className="mt-6 space-y-5 text-zinc-800">
                {contact.person_name?.trim() ? (
                    <div className="flex gap-3">
                        <dt className="sr-only">{labels.role}</dt>
                        <dd className="flex flex-1 gap-3">
                            <span className="mt-0.5 inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#4b1f6f]/10 text-[#4b1f6f]">
                                <User size={18} aria-hidden />
                            </span>
                            <div>
                                <p className="font-semibold text-zinc-900">{contact.person_name.trim()}</p>
                                {contact.role?.trim() ? (
                                    <p className="mt-0.5 text-sm text-zinc-600">{contact.role.trim()}</p>
                                ) : null}
                            </div>
                        </dd>
                    </div>
                ) : contact.role?.trim() ? (
                    <div className="flex gap-3">
                        <span className="mt-0.5 inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#4b1f6f]/10 text-[#4b1f6f]">
                            <User size={18} aria-hidden />
                        </span>
                        <div>
                            <dt className="text-xs font-semibold uppercase tracking-wide text-zinc-500">{labels.role}</dt>
                            <dd className="mt-1 text-zinc-900">{contact.role.trim()}</dd>
                        </div>
                    </div>
                ) : null}

                {contact.schedule?.trim() ? (
                    <div className="flex gap-3">
                        <span className="mt-0.5 inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#4b1f6f]/10 text-[#4b1f6f]">
                            <Clock size={18} aria-hidden />
                        </span>
                        <div>
                            <dt className="text-xs font-semibold uppercase tracking-wide text-zinc-500">{labels.schedule}</dt>
                            <dd className="mt-1 whitespace-pre-line text-zinc-800">{contact.schedule.trim()}</dd>
                        </div>
                    </div>
                ) : null}

                {contact.location?.trim() ? (
                    <div className="flex gap-3">
                        <span className="mt-0.5 inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#4b1f6f]/10 text-[#4b1f6f]">
                            <MapPin size={18} aria-hidden />
                        </span>
                        <div>
                            <dt className="text-xs font-semibold uppercase tracking-wide text-zinc-500">{labels.location}</dt>
                            <dd className="mt-1 whitespace-pre-line text-zinc-800">{contact.location.trim()}</dd>
                        </div>
                    </div>
                ) : null}

                {contact.phone?.trim() ? (
                    <div className="flex gap-3">
                        <span className="mt-0.5 inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#4b1f6f]/10 text-[#4b1f6f]">
                            <Phone size={18} aria-hidden />
                        </span>
                        <div>
                            <dt className="text-xs font-semibold uppercase tracking-wide text-zinc-500">{labels.phone}</dt>
                            <dd className="mt-1">
                                <a href={`tel:${contact.phone.replace(/\s+/g, '')}`} className="font-medium text-[#4b1f6f] underline-offset-2 hover:underline">
                                    {contact.phone.trim()}
                                </a>
                            </dd>
                        </div>
                    </div>
                ) : null}

                {contact.email?.trim() ? (
                    <div className="flex gap-3">
                        <span className="mt-0.5 inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#4b1f6f]/10 text-[#4b1f6f]">
                            <Mail size={18} aria-hidden />
                        </span>
                        <div>
                            <dt className="text-xs font-semibold uppercase tracking-wide text-zinc-500">{labels.email}</dt>
                            <dd className="mt-1">
                                <a href={`mailto:${contact.email.trim()}`} className="break-all font-medium text-[#4b1f6f] underline-offset-2 hover:underline">
                                    {contact.email.trim()}
                                </a>
                            </dd>
                        </div>
                    </div>
                ) : null}
            </dl>
        </div>
    );
}
