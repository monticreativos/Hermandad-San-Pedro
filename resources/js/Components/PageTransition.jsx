import { motion, useReducedMotion } from 'framer-motion';

/**
 * Evita pantalla en blanco en móviles con "reducir movimiento" o WebKit que no aplica bien opacity inicial.
 */
export default function PageTransition({ children }) {
    const reduceMotion = useReducedMotion();

    if (reduceMotion) {
        return <div className="contents">{children}</div>;
    }

    return (
        <motion.div
            initial={{ y: 8 }}
            animate={{ y: 0 }}
            transition={{ duration: 0.28, ease: 'easeOut' }}
        >
            {children}
        </motion.div>
    );
}
