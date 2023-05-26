<?php

namespace App\Tests\Unit\Security;

use App\Entity\User;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use SymfonyCasts\Bundle\VerifyEmail\Model\VerifyEmailSignatureComponents;

class EmailVerifierTest extends TestCase
{
    public function testSendEmailConfirmation()
    {
        $user = $this->createMock(User::class);
        $user->method('getId')->willReturn(2);
        $user->method('getEmail')->willReturn('test@example.com');

        $email = new TemplatedEmail();

        $lifetime = 3600; // 1 hour
        $expiresAt = (new \DateTime())->add(new \DateInterval('PT' . $lifetime . 'S'));
        $signatureComponents = new VerifyEmailSignatureComponents($expiresAt, 'https://example.com', $lifetime);

        $verifyEmailHelper = $this->createMock(VerifyEmailHelperInterface::class);
        $verifyEmailHelper->expects($this->once())
            ->method('generateSignature')
            ->with(
                'email_verification_route',
                $user->getId(),
                $user->getEmail(),
                ['id' => $user->getId()]
            )
            ->willReturn($signatureComponents);

        $mailer = $this->createMock(MailerInterface::class);
        $mailer->expects($this->once())
            ->method('send')
            ->with($this->callback(function ($subject) use ($signatureComponents) {
                return $subject->getContext()['signedUrl'] === $signatureComponents->getSignedUrl();
            }));

        $emailVerifier = new EmailVerifier(
            $verifyEmailHelper,
            $mailer,
            $this->createMock(EntityManagerInterface::class)
        );

        $emailVerifier->sendEmailConfirmation('email_verification_route', $user, $email);
    }

    public function testHandleEmailConfirmation()
    {
        $user = $this->createMock(User::class);
        $user->method('getId')->willReturn(2);
        $user->method('getEmail')->willReturn('test@example.com');
        $user->expects($this->once())
            ->method('setIsVerified')
            ->with(true);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())
            ->method('persist')
            ->with($user);
        $entityManager->expects($this->once())
            ->method('flush');

        $request = $this->createMock(Request::class);
        $request->method('getUri')->willReturn('http://example.com');
        $verifyEmailHelper = $this->createMock(VerifyEmailHelperInterface::class);
        $verifyEmailHelper->expects($this->once())
            ->method('validateEmailConfirmation')
            ->with(
                $request->getUri(),
                $user->getId(),
                $user->getEmail()
            );

        $emailVerifier = new EmailVerifier(
            $verifyEmailHelper,
            $this->createMock(MailerInterface::class),
            $entityManager
        );

        $emailVerifier->handleEmailConfirmation($request, $user);
    }

    public function testHandleEmailConfirmationThrowsException()
    {
        $this->expectException(VerifyEmailExceptionInterface::class);

        $user = $this->createMock(User::class);
        $user->method('getId')->willReturn(2);
        $user->method('getEmail')->willReturn('test@example.com');

        $request = $this->createMock(Request::class);
        $request->method('getUri')->willReturn('http://example.com');
        $verifyEmailHelper = $this->createMock(VerifyEmailHelperInterface::class);
        $verifyEmailHelper->expects($this->once())
            ->method('validateEmailConfirmation')
            ->with(
                $request->getUri(),
                $user->getId(),
                $user->getEmail()
            )
            ->will($this->throwException($this->createMock(VerifyEmailExceptionInterface::class)));

        $emailVerifier = new EmailVerifier(
            $verifyEmailHelper,
            $this->createMock(MailerInterface::class),
            $this->createMock(EntityManagerInterface::class)
        );

        $emailVerifier->handleEmailConfirmation($request, $user);
    }
}
