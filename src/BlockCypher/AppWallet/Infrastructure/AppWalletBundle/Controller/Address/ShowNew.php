<?php

namespace BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\Address;

use BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\AppWalletController;
use BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Form\Address\AddressFormFactory;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;

class ShowNew extends AppWalletController
{
    /**
     * @var AddressFormFactory
     */
    private $addressFormFactory;

    /**
     * @param EngineInterface $templating
     * @param TranslatorInterface $translator
     * @param AddressFormFactory $walletFormFactory
     */
    public function __construct(
        EngineInterface $templating,
        TranslatorInterface $translator,
        AddressFormFactory $walletFormFactory)
    {
        parent::__construct($templating, $translator);
        $this->addressFormFactory = $walletFormFactory;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(Request $request)
    {
        $walletId = $request->get('walletId');

        $tag = '';
        $callbackUrl = '';
        $createAddressCommand = $this->createCreateAddressCommand($walletId, $tag, $callbackUrl);

        $createAddressForm = $this->addressFormFactory->createCreateForm($createAddressCommand, $walletId);

        $template = $this->getBaseTemplatePrefix() . ':Address:show_new.html';

        return $this->templating->renderResponse(
            $template . '.' . $this->getEngine(),
            array(
                // TODO: move to base controller and merge arrays
                'is_home' => false,
                'user' => array('is_authenticated' => true),
                'messages' => array(),
                //
                'coin_symbol' => 'btc',
                'address_form' => $createAddressForm->createView(),
                'wallet_id' => $walletId
            )
        );
    }
}