  <div class="form pb-5 scroll-animate" id="contacto">
        
        <div class="container ">
            <div class="titular">
                <h4>¿Necesitas espacio extra?</h4>
                <p>Déjanos tus datos y te ayudamos a elegir la minibodega perfecta para lo que quieres guardar. Respuesta rápida y sin compromiso.</p>
            </div>
            <form class="row g-3 formulario">
                <div class="form_contenido">
                    <div class="col-md-12 pb-3">
                        <input type="name" placeholder="NOMBRE Y APELLIDO" class="form-control" id="name">
                    </div>
                    <div class="row">
                        <div class="col-md-6 pb-3">
                            <input type="tel" placeholder="TELÉFONO" class="form-control" id="tel">
                        </div>
                        <div class="col-md-6">
                            <input type="email" placeholder="EMAIL" class="form-control" id="email">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 pb-3">
                            <input type="text" placeholder="¿QUÉ TE GUSTARÍA ALMACENAR?" class="form-control" id="empresa">
                        </div>
                        <div class="col-md-6">
                            <input type="text" placeholder="CIUDAD" class="form-control" id="ciudad">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="gridCheck">
                            <label class="form-check-label " for="gridCheck">
                                Autorizo a Maxispace a contactarme por WhatsApp, llamada o correo electrónico para
                                brindarme información sobre sus servicios.
                            </label>
                        </div>
                    </div>
                    <div class="col-12 mt-2" style="display: flex; flex-direction: row; justify-content: center; align-items: center">
                        <button type="submit" class="btn btn-maxiblue arrow btn-block">
                            ENVIAR POR WHATSAPP <span>
                                <img src="{{ asset('/img/Arrow.svg') }}" width="18" alt="">
                            </span>
                        </button>
                    </div>
                    <div class="col-12 text-center mt-2">Recibirás comunicaciones por parte de nuestros asesores para
                        brindarte atención completa y personalizada, además de correos electrónicos con fines
                        informativos.
                    </div>
                </div>
            </form>
        </div>
    </div>