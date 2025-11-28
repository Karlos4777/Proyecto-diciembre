@extends('plantilla.app')

@section('titulo', 'Política de Privacidad')

@section('contenido')
<div class="container py-5">
    <h1>Política de Privacidad</h1>
    <p class="text-muted">Versión 1.0 - Última actualización: Noviembre 2025</p>

    <h2>1. Datos que recolectamos</h2>
    <ul>
        <li>Datos personales que el usuario proporciona: nombre, correo electrónico, teléfono, dirección.</li>
        <li>Datos técnicos y de navegación: cookies, IP, agente de usuario.</li>
    </ul>

    <h2>2. Uso de los datos</h2>
    <ul>
        <li>Procesamiento de pedidos.</li>
        <li>Comunicación con el cliente.</li>
        <li>Cumplimiento de obligaciones legales.</li>
    </ul>

    <h2>3. Conservación</h2>
    <p>Se conservarán mientras la cuenta exista y los requerimientos legales lo exijan.</p>

    <h2>4. Consentimiento de cookies</h2>
    <p>El consentimiento se registra en la base de datos para usuarios autenticados o en una cookie para invitados. La cookie de consentimiento tiene duración de 1 año.</p>

    <h2>5. Contacto</h2>
    <p>Para cualquier consulta relacionada con privacidad, puede contactarnos en: <a href="mailto:soporte@ejemplo.com">soporte@ejemplo.com</a></p>
</div>
@endsection
