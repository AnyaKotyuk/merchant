<?php

namespace App\Controller;

use App\Entity\Payment;
use App\Form\PaymentType;
use App\Service\NotifyClientService;
use App\Service\PaymentResponseDataService;
use App\Service\SignatureService;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class MerchantController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('client.html.twig');
    }

    /**
     * @Route("/form", name="get_form", methods={"POST"})
     *
     * @param Request $request
     * @param SignatureService $signatureService
     * @return Response
     */
    public function getPaymentForm(Request $request, SignatureService $signatureService): Response
    {
        $amount = $request->request->get('amount');
        $currency = $request->request->get('currency');
        $callbackUrl = $request->request->get('callbackUrl');

        $signature = $signatureService->createSign($amount, $currency, $callbackUrl);

        $payment = new Payment();

        $payment->setAmount($amount);
        $payment->setCurrency($currency);

        $paymentForm = $this->createForm(PaymentType::class, $payment)
            ->add('signature', HiddenType::class, ['data' => $signature, 'mapped' => false])
            ->add('callbackUrl', HiddenType::class, ['data' => $callbackUrl, 'mapped' => false]);

        return $this->render('payment-form.html.twig', ['form' => $paymentForm->createView()]);
    }

    /**
     * @Route("/confirm", name="confirm_payment", methods={"POST"})
     *
     * @param Request $request
     * @param PaymentResponseDataService $paymentResponseDataService
     * @param NotifyClientService $notifyClientService
     * @return Response
     */
    public function processPayment(
        Request $request,
        PaymentResponseDataService $paymentResponseDataService,
        NotifyClientService $notifyClientService
    ): Response {
        $paymentData = $request->request->get('payment');

        $payment = new Payment();
        $paymentForm = $this->createForm(PaymentType::class, $payment, ['allow_extra_fields' => true]);

        $paymentForm->handleRequest($request);

        if ($paymentForm->isSubmitted() && $paymentForm->isValid()) {
            if ($paymentData['card']['number'] == '1111') {
                $notifyRequest = $paymentResponseDataService->getErrorResponse('Something went wrong');
                $paymentResult = 'Something went wrong';
            } else {
                $notifyRequest = $paymentResponseDataService->getSuccessResponse($paymentData);
                $paymentResult = 'Payment is successful';
            }
        } else {
            $validationErrors = $paymentForm->getErrors(true);
            $notifyRequest = $paymentResponseDataService->getErrorResponse($validationErrors);
            $paymentResult = $validationErrors;
        }

        $callbackUrl = $paymentData['callbackUrl'];
        $notifyClientService->notify($callbackUrl, $notifyRequest);

        return $this->render('payment-result.html.twig', [
            'result' => $paymentResult,
            'merchant_url' => $callbackUrl
        ]);
    }

    /**
     * @Route("/callback", name="callback")
     *
     * @param Request $request
     * @param LoggerInterface $logger
     * @return Response
     */
    public function call(Request $request, LoggerInterface $logger): Response
    {
        $logger->log(LogLevel::INFO, json_encode($request));

        return new Response();
    }
}