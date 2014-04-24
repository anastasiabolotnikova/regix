package ee.ut.mt.webphp.tests;

import java.util.concurrent.TimeUnit;

import junit.framework.TestCase;

import org.junit.*;
import org.openqa.selenium.*;
import org.openqa.selenium.firefox.FirefoxDriver;
import org.openqa.selenium.support.ui.Select;

public class PermissionManagerFirefoxTest extends TestCase {
	private WebDriver driver;
	private String baseUrl;
	private StringBuffer verificationErrors = new StringBuffer();

	@Before
	public void setUp() throws Exception {
		driver = new FirefoxDriver();
		baseUrl = "http://regix.dev/";
		driver.manage().timeouts().implicitlyWait(3, TimeUnit.SECONDS);
		
		// Login as admin
	    driver.get(baseUrl + "login");
	    driver.findElement(By.id("login")).clear();
	    driver.findElement(By.id("login")).sendKeys("test");
	    driver.findElement(By.id("password")).clear();
	    driver.findElement(By.id("password")).sendKeys("test_password");
	    driver.findElement(By.name("submit")).click();
	}

	public void addCategory() throws Exception {
		driver.get(baseUrl + "permissions/categories");
		driver.findElement(By.cssSelector("button.secondary-button-single.last")).click();
		driver.findElement(By.id("name")).clear();
		driver.findElement(By.id("name")).sendKeys("test_category");
		driver.findElement(By.name("submit")).click();
		assertEquals("Category added", driver.findElement(By.cssSelector("div.success_message_block")).getText());
		driver.get(baseUrl + "permissions/add");
		assertTrue(isElementPresent(By.xpath("//option[contains(text(), \"test_category\")]")));
	}
	
	public void addExistingCategory() throws Exception {
		driver.get(baseUrl + "permissions/categories");
		driver.findElement(By.cssSelector("button.secondary-button-single.last")).click();
		driver.findElement(By.id("name")).clear();
		driver.findElement(By.id("name")).sendKeys("test_category");
		driver.findElement(By.name("submit")).click();
		assertEquals("Could not add category", driver.findElement(By.cssSelector("div.error_message_block")).getText());
	}
	
	public void addPermission() throws Exception {
		driver.get(baseUrl + "permissions");
		driver.findElement(By.xpath("//button[contains(text(), 'Add permission')]")).click();
	    driver.findElement(By.id("name")).clear();
	    driver.findElement(By.id("name")).sendKeys("test_permission");
	    new Select(driver.findElement(By.id("category_name"))).selectByVisibleText("test_category");
	    driver.findElement(By.id("description")).clear();
	    driver.findElement(By.id("description")).sendKeys("test_description");
	    driver.findElement(By.name("submit")).click();
	    assertEquals("Permission added", driver.findElement(By.cssSelector("div.success_message_block")).getText());
	    driver.get(baseUrl + "permissions");
	    assertTrue(isElementPresent(By.xpath("//div[contains(text(), 'test_permission')]")));
	    assertTrue(isElementPresent(By.xpath("//div[contains(text(), 'test_category')]")));
	}
	
	public void addExistingPermission() throws Exception {
		driver.get(baseUrl + "permissions");
		driver.findElement(By.xpath("//button[contains(text(), 'Add permission')]")).click();
	    driver.findElement(By.id("name")).clear();
	    driver.findElement(By.id("name")).sendKeys("test_permission");
	    new Select(driver.findElement(By.id("category_name"))).selectByVisibleText("test_category");
	    driver.findElement(By.id("description")).clear();
	    driver.findElement(By.id("description")).sendKeys("test_description");
	    driver.findElement(By.name("submit")).click();
	    assertEquals("Could not add permission", driver.findElement(By.cssSelector("div.error_message_block")).getText());
	    driver.get(baseUrl + "permissions");
	}
	
	public void removePermission() throws Exception {
		driver.get(baseUrl + "permissions");
		driver.findElement(By.xpath("//div[contains(text(), 'test_category')]/../../td/div/a")).click();
		assertEquals("Permission deleted", driver.findElement(By.cssSelector("div.success_message_block")).getText());
		driver.get(baseUrl + "permissions");
		assertFalse(isElementPresent(By.xpath("//div[contains(text(), 'test_permission')]")));
	}
	
	public void removeNonexistentPermission() throws Exception {
		driver.get(baseUrl + "permissions/delete/test_permission");
		assertEquals("Could not delete permission", driver.findElement(By.cssSelector("div.error_message_block")).getText());
	}

	public void removeCategory() throws Exception {
		driver.get(baseUrl + "permissions/categories");
		driver.findElement(By.xpath("//td[contains(text(),'test_category')]/../td/a")).click();
		assertEquals("Category deleted", driver.findElement(By.cssSelector("div.success_message_block")).getText());
		driver.get(baseUrl + "permissions/add");
		assertFalse(isElementPresent(By.xpath("//option[contains(text(), \"test_category\")]")));
	}
	
	public void removeNonexistentCategory() throws Exception {
		driver.get(baseUrl + "permissions/delete_category/0");
		assertEquals("Could not delete category", driver.findElement(By.cssSelector("div.error_message_block")).getText());
	}
	
	@Test
	public void testAll() throws Exception {
		addCategory();
		addExistingCategory();
		addPermission();
		addExistingPermission();
		removePermission();
		removeNonexistentPermission();
		removeCategory();
		removeNonexistentCategory();
	}

	@After
	public void tearDown() throws Exception {
		driver.quit();
		String verificationErrorString = verificationErrors.toString();
		if (!"".equals(verificationErrorString)) {
			fail(verificationErrorString);
		}
	}

	private boolean isElementPresent(By by) {
		try {
			driver.findElement(by);
			return true;
		} catch (NoSuchElementException e) {
			return false;
		}
	}
}