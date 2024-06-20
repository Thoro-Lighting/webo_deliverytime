<?php

declare(strict_types=1);

namespace WeboDeliveryTime\Controller\Admin;

use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WeboDeliveryTime\Entity\WeboDeliveryTimeShipping;
use WeboDeliveryTime\Grid\Filter\DeliveryTimeShippingFilters;

class DeliveryTimeController extends FrameworkBundleAdminController {

    public function indexAction(DeliveryTimeShippingFilters $filters) {
        $deliveryTimeGridFactory = $this->get('webo_delivery_time.grid.delivery_time_shipping_grid_factory');
        $deliveryTimeGrid = $deliveryTimeGridFactory->getGrid($filters);

        return $this->render('@Modules/webo_deliverytime/views/templates/admin/grid/grid.html.twig', [
            'deliveryTimeGrid' => $this->presentGrid($deliveryTimeGrid),
            'addUrl' => $this->generateUrl('webo_delivery_time_form_create'),
        ]);
    }

    public function createAction(Request $request): Response
    {
        $deliveryTimeShippingFormBuilder = $this->get('webo_delivery_time.form.builder.delivery_time_shipping_form_builder');
        $deliveryTimeShippingForm = $deliveryTimeShippingFormBuilder->getForm();

        $deliveryTimeShippingForm->handleRequest($request);
        $formHandler = $this->get('webo_delivery_time.form.identifiable_object.handler.delivery_time_shipping_form_handler');

        try {
            $result = $formHandler->handle($deliveryTimeShippingForm);

            if (null !== $result->getIdentifiableObjectId()) {

                $this->addFlash(
                    'success',
                    $this->trans('Successful add.', 'Admin.Notifications.Success')
                );

                return $this->redirectToRoute('webo_delivery_time_list');
            }

        } catch (\Exception $e) {;
            $this->addFlash('error', $e->getMessage());
        };

        return $this->render('@Modules/webo_deliverytime/views/templates/admin/form/form.html.twig', [
            'form' => $deliveryTimeShippingForm->createView(),
            'backUrl' => $this->generateUrl('webo_delivery_time_list')
        ]);
    }

    public function editAction(Request $request, int $id): Response
    {
        $deliveryTimeShippingFormBuilder = $this->get('webo_delivery_time.form.builder.delivery_time_shipping_form_builder');
        $deliveryTimeShippingForm = $deliveryTimeShippingFormBuilder->getFormFor($id);

        $deliveryTimeShippingForm->handleRequest($request);
        $formHandler = $this->get('webo_delivery_time.form.identifiable_object.handler.delivery_time_shipping_form_handler');

        try {
            $result = $formHandler->handleFor($id, $deliveryTimeShippingForm);

            if (null !== $result->getIdentifiableObjectId()) {
                $this->addFlash(
                    'success',
                    $this->trans('Successful edition.', 'Admin.Notifications.Success')
                );

                return $this->redirectToRoute('webo_delivery_time_list');
            }
        } catch (\Exception $e) {;
            $this->addFlash('error', $e->getMessage());
        };

        return $this->render('@Modules/webo_deliverytime/views/templates/admin/form/form.html.twig', [
            'form' => $deliveryTimeShippingForm->createView(),
            'backUrl' => $this->generateUrl('webo_delivery_time_list')
        ]);
    }

    public function deleteAction(Request $request,  int $id): Response
    {
        $weboDeliveryItemShipping = $this->getDoctrine()
            ->getRepository(WeboDeliveryTimeShipping::class)
            ->find($id);

        if(!empty($christmasCalendarItem)) {
            $entityManager = $this->get('doctrine.orm.entity_manager');

            $entityManager->remove($weboDeliveryItemShipping);
            $entityManager->flush();

            $this->addFlash(
                'success',
                $this->trans('Successful deletion.', 'Admin.Notifications.Success')
            );

            return $this->redirectToRoute('webo_delivery_time_list');
        }

        $this->addFlash(
            'error',
            $this->trans('Cannot find shipping delivery item element %d', 'Admin.Notifications.Error', ['%d' => $id])
        );

        return $this->redirectToRoute('webo_delivery_time_list');
    }

}