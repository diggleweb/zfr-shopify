<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

namespace ZfrShopifyTest\Container;

use Interop\Container\ContainerInterface;
use ZfrShopify\Exception\RuntimeException;
use ZfrShopify\Container\ShopifyClientFactory;
use ZfrShopify\ShopifyClient;

/**
 * @author Michaël Gallego
 */
class ShopifyClientFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testThrowExceptionIfNoConfig()
    {
        $this->setExpectedException(RuntimeException::class);

        $container = $this->getMock(ContainerInterface::class);
        $container->expects($this->once())->method('has')->with('config')->willReturn(true);
        $container->expects($this->once())->method('get')->with('config')->willReturn([]);

        $factory = new ShopifyClientFactory();
        $factory->__invoke($container);
    }

    public function testThrowExceptionIfMandatoryParametersAreMissing()
    {
        $this->setExpectedException(RuntimeException::class);

        $container = $this->getMock(ContainerInterface::class);
        $container->expects($this->once())->method('has')->with('config')->willReturn(true);
        $container->expects($this->once())->method('get')->with('config')->willReturn([
            'zfr_shopify' => [
                'private_app' => true
            ]
        ]);

        $factory = new ShopifyClientFactory();
        $factory->__invoke($container);
    }

    public function testCanCreateService()
    {
        $container = $this->getMock(ContainerInterface::class);
        $container->expects($this->once())->method('has')->with('config')->willReturn(true);
        $container->expects($this->once())->method('get')->with('config')->willReturn([
            'zfr_shopify' => [
                'shared_secret' => 'foo',
                'api_key'       => 'bar',
                'private_app'   => false
            ]
        ]);

        $factory = new ShopifyClientFactory();
        $client  = $factory->__invoke($container);

        $this->assertInstanceOf(ShopifyClient::class, $client);
    }
}